<?php

namespace App\Controller;

use App\Entity\Session;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/session-details/{id}', name: 'app_session_details', requirements: ['id' => '\d+'])]
    public function details(Session $session, Request $request): Response
    {
        $referer = $request->headers->get('referer');

        return $this->render('session/session-details.html.twig', [
            'session' => $session,
            'previousUrl' => $referer ?? '/',
        ]);
    }
}
