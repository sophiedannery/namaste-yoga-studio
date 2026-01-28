<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use App\Service\ReservationService;
use App\Stats\StatsCounter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservationController extends AbstractController
{

    #[Route('/reservation/{id}/reserver', name: 'app_reservation_reserver', methods: ['POST'])]
    public function reserver(
        int $id, 
        Request $request, 
        SessionRepository $session_repository, 
        StatsCounter $counter,
        ReservationService $reservationService
        ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if (!$this->isCsrfTokenValid('reserver' . $id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Requête invalide, token CSRF non validé.');
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        $session = $session_repository->find($id);
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$session) {
            $this->addFlash('error', 'Session introuvable.');
            return $this->redirectToRoute('app_session_planning');
        }

        $result = $reservationService->reserve($session, $user);

        if(!empty($result['errors'])) {
            foreach ($result['errors'] as $msg) {
                $this->addFlash('error', $msg);
            }
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        $counter->incConfirmed(1);

        $this->addFlash('success', 'Votre réservation est confirmée !');
        return $this->redirectToRoute('app_profile_cours');
    }
}
