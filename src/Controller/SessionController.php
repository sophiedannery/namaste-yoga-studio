<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionForm;
use App\Repository\ReservationRepository;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Stats\StatsCounter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class SessionController extends AbstractController
{
    #[Route('/planning', name: 'app_session_planning')]
    public function planning(SessionRepository $session_repository, UserRepository $user_repository): Response
    {
        $sessions = $session_repository->findAll();
        $teacher = $user_repository->findAll();

        return $this->render('session/session-planning.html.twig', [
            'sessions' => $sessions,
            'teacher' => $teacher,
        ]);
    }


    #[Route('/sessions/fragment', name: 'sessions_fragment', methods: ['GET'])]
    public function fragment(Request $request, SessionRepository $repo)
    {
        $level   = $request->query->get('level');
        $teacher = $request->query->get('teacher');
        $style   = $request->query->get('style');

        $sessions = $repo->findByFilters($level, $teacher, $style);

        return $this->render('session/session-partial-card.html.twig', [
            'sessions' => $sessions,
        ]);
    }



    #[Route('/session-details/{id}', name: 'app_session_details', requirements: ['id' => '\d+'])]
    public function details(Session $session, Request $request, ReservationRepository $reservation_repository): Response
    {
        $referer = $request->headers->get('referer');

        $active = $reservation_repository->countActiveBySession($session);
        $remaining = max(0, $session->getCapacity() - $active);

        return $this->render('session/session-details.html.twig', [
            'session' => $session,
            'previousUrl' => $referer ?? '/',
            'remaining' => $remaining,
        ]);
    }


    #[Route('/session/ajout', name: 'app_session_new')]
    #[IsGranted('ROLE_TEACHER')]
    public function newSession(StatsCounter $counter, Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
            $teacher = $this->getUser();
        $session = new Session();
        $form = $this->createForm(SessionForm::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $session->setTeacher($teacher);
            $session->setStatus('SCHEDULED');
            $session->setUpdatedAt(new \DateTimeImmutable('now'));

            $counter->incCreated(1);

            $em->persist($session);
            $em->flush();

            $this->addFlash('success', 'Cours ajouté avec succès !');
            return $this->redirectToRoute('app_profile_teacher_planning');

        }
        
        return $this->render('session/session-new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/session/{id}/annuler', name: 'app_session_annuler', methods: ['POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function annulerSession(
        StatsCounter $counter,
        Session $session,
        EntityManagerInterface $em,
        Request $request): Response
    {
        if (!$this->isCsrfTokenValid('cancel_session' . $session->getId(), $request->request->get('_token'))) {
        $this->addFlash('error', 'Token invalide.');
        return $this->redirectToRoute('app_profile_teacher_planning');
        }

        if ($session->getTeacher() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
        }

        $session->setStatus('CANCELLED');
        $session->setCancelledAt(new \DateTimeImmutable('now'));
        $session->setCancelledBy($this->getUser());
        $session->setUpdatedAt(new \DateTimeImmutable('now'));

        $counter->decCreated(1);

        $em->flush();

        $this->addFlash('success', 'Votre cours a bien été annulé.');

        return $this->redirectToRoute('app_profile_teacher_planning');

    }
}
