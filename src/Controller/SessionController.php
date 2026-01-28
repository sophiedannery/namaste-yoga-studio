<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionForm;
use App\Service\ReservationService;
use App\Service\SessionService;
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

    #[Route('/session-details/{id}', name: 'app_session_details', requirements: ['id' => '\d+'])]
    public function details(
        Session $session, 
        Request $request,
        ReservationService $reservationService
        ): Response
    {
        $referer = $request->headers->get('referer');
        $remaining = $reservationService->getRemainingPlaces($session);
        return $this->render('session/session-details.html.twig', [
            'session' => $session,
            'previousUrl' => $referer ?? '/',
            'remaining' => $remaining,
        ]);
    }

    #[Route('/session/ajout', name: 'app_session_new')]
    #[IsGranted('ROLE_TEACHER')]
    public function newSession(
        Request $request, 
        SessionService $sessionService
        ): Response
    {

        /** @var \App\Entity\User $user */
            $teacher = $this->getUser();
            
        $session = new Session();
        $sessionService->prepareNewSession($session, $teacher);

        $form = $this->createForm(SessionForm::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sessionService->create($session);
            $this->addFlash('success', 'Cours ajouté avec succès !');
            return $this->redirectToRoute('app_profile_teacher_planning');
        }

        return $this->render('session/session-new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
