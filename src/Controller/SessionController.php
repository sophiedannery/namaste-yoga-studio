<?php

/**
 * SessionController
 * -----------------------------------------------------------------------------
 * Purpose:
 *   Manage yoga class sessions.
 *
 * What it does:
 *   - Show the planning.
 *   - Show the detail page of a single session.
 *   - Allow teachers to create and cancel their own sessions.
 */

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionForm;
use App\Repository\ReservationRepository;
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
    public function planningTest(): Response
    {
        
        return $this->render('session/session-planning.html.twig', [
        ]);
    }


    /**
     * Show a single session details page.
     *
     * GET /session-details/{id}
     * Route requires id.
     */
    #[Route('/session-details/{id}', name: 'app_session_details', requirements: ['id' => '\d+'])]
    public function details(Session $session, Request $request, ReservationRepository $reservation_repository): Response
    {
        $referer = $request->headers->get('referer');

        // Remaining seats = capacity - active reservations (never negative).
        $active = $reservation_repository->countActiveBySession($session);
        $remaining = max(0, $session->getCapacity() - $active);

        return $this->render('session/session-details.html.twig', [
            'session' => $session,
            'previousUrl' => $referer ?? '/',
            'remaining' => $remaining,
        ]);
    }


    /**
     * Create a new session (teacher only).
     *
     * GET|POST /session/ajout
     *   - GET: display the creation form.
     *   - POST: validate, set teacher/status, persist, update stats, redirect.
     */
    #[Route('/session/ajout', name: 'app_session_new')]
    #[IsGranted('ROLE_TEACHER')]
    public function newSession(
        StatsCounter $counter, 
        Request $request, 
        EntityManagerInterface $em
        ): Response
    {
        /** @var \App\Entity\User $user */
            $teacher = $this->getUser();
            
        $session = new Session();

        // Ensure the session is owned by the logged-in teacher.
        $session->setTeacher($teacher);


        $form = $this->createForm(SessionForm::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
             // Initial status for a new class.
            $session->setStatus('SCHEDULED');
            $session->setUpdatedAt(new \DateTimeImmutable('now'));

            // Update stats
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

}
