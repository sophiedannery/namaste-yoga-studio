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

    // =========================
    // Réserver une session (élève)
    // =========================
    #[Route('/reservation/{id}/reserver', name: 'app_reservation_reserver', methods: ['POST'])]
    public function reserver(
        int $id,
        Request $request, 
        SessionRepository $session_repository,
        StatsCounter $counter,
        ReservationService $reservationService
        ): Response
    {
        // Vérifie que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Vérification du token CSRF
        if (!$this->isCsrfTokenValid('reserver' . $id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Requête invalide, token CSRF non validé.');
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

         // Récupération de la session à partir de son identifiant
        $session = $session_repository->find($id);

        // Récupération de l'utilisateur connecté
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Si la session n'existe pas
        if (!$session) {
            $this->addFlash('error', 'Session introuvable.');
            return $this->redirectToRoute('app_session_planning');
        }

        // Appel du service Réservation pour effectuer la réservation
        $result = $reservationService->reserve($session, $user);

        // Si des erreurs métier sont retournées
        if(!empty($result['errors'])) {
            foreach ($result['errors'] as $msg) {
                $this->addFlash('error', $msg);
            }
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        // Incrémentation du compteur
        $counter->incConfirmed(1);

        // Message de confirmation pour l'utilisateur
        $this->addFlash('success', 'Votre réservation est confirmée !');

        // Redirection vers l’espace personnel de l’élève
        return $this->redirectToRoute('app_profile_cours');
    }
}
