<?php

/**
 * ReservationController
 * -----------------------------------------------------------------------------
 * Purpose:
 *   Manage student reservations for sessions (book & cancel).
 *
 * What it does:
 *   - Allow an authenticated user to reserve a session.
 *   - Allow the same user to cancel their reservation.
 */

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\SessionRepository;
use App\Service\ReservationService;
use App\Stats\StatsCounter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ReservationController extends AbstractController
{

     /**
     * Reserve a place in a session for the current user.
     *
     * POST /reservation/{id}/reserver
     */
    #[Route('/reservation/{id}/reserver', name: 'app_reservation_reserver', methods: ['POST'])]
    public function reserver(
        int $id, 
        Request $request, 
        SessionRepository $session_repository, 
        EntityManagerInterface $em, 
        ReservationRepository $reservation_repository, 
        StatsCounter $counter,
        ValidatorInterface $validator, 
        ReservationService $rules
        ): Response
    {
        // Require authenticated user.
        $this->denyAccessUnlessGranted('ROLE_USER');

        // CSRF protection
        if (!$this->isCsrfTokenValid('reserver' . $id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Requête invalide, token CSRF non validé.');
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        // Fetch the target session
        $session = $session_repository->find($id);
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Guard: session must exist
        if (!$session) {
            $this->addFlash('error', 'Session introuvable.');
            return $this->redirectToRoute('app_session_planning');
        }

        $errors = $rules->validateCanReserve($session, $user);
        if(!empty($errors)) {
            foreach ($errors as $msg) {
                $this->addFlash('error', $msg);
            }
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        // Create the reservation and set initial status
        $reservation = new Reservation();
        $reservation 
            ->setSession($session)
            ->setStudent($user)
            ->setStatut('CONFIRMED')
            ->setBookedAt(new \DateTimeImmutable());

        // ✅ Validation des Assert de Reservation
        $errors = $validator->validate($reservation);
        if (count($errors) > 0) {
            // En prod tu ferais plutôt un flash + log
            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        // Update stats 
        $counter->incConfirmed(1);
        // Persist and commit.
        $em->persist($reservation);
        $em->flush();

        $this->addFlash('success', 'Votre réservation est confirmée !');
        return $this->redirectToRoute('app_profile_cours');
    }
}
