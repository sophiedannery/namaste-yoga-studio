<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SessionController extends AbstractController
{
    #[Route('/planning', name: 'app_session_planning')]
    public function planning(SessionRepository $session_repository): Response
    {
        $sessions = $session_repository->findAll();

        return $this->render('session/session-planning.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    #[Route('/session-details', name: 'app_session_details')]
    public function details(): Response
    {
        return $this->render('session/session-details.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }
}
