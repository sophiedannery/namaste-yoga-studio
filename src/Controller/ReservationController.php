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
use App\Stats\StatsCounter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservationController extends AbstractController
{

     /**
     * Reserve a place in a session for the current user.
     *
     * POST /reservation/{id}/reserver
     */
    #[Route('/reservation/{id}/reserver', name: 'app_reservation_reserver', methods: ['POST'])]
    public function reserver(int $id, Request $request, SessionRepository $session_repository, EntityManagerInterface $em, ReservationRepository $reservation_repository, StatsCounter $counter): Response
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

        // Guard: teachers cannot book their own class.
        if ($session->getTeacher() === $user) {
            $this->addFlash('error', 'Vous ne pouvez pas participer à votre propre cours.');
            return $this->redirectToRoute('app_session_planning', ['id' => $id]);
        }

        // Guard: cannot book after the class has started
        if ((new \DateTimeImmutable('now')) >= $session->getStartAt()) {
            $this->addFlash('warning', 'Le cours a commencé : réservation impossible.');
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        // Prevent duplicate active reservations for this user & session
        $exists = $reservation_repository->findOneBy([
            'session' => $session,
            'student' => $user
        ]);
        if ($exists && $exists->getCancelledAt() === null) {
            $this->addFlash('warning', 'Vous participez déjà à ce cours.');
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        // Capacity check
        $active = $reservation_repository->countActiveBySession($session); 
        $remaining = $session->getCapacity() - $active;
        if ($remaining <= 0) {
        $this->addFlash('error', 'Plus de place disponible sur ce cours.');
        return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        // Create the reservation and set initial status
        $reservation = new Reservation();
        $reservation 
            ->setSession($session)
            ->setStudent($user)
            ->setStatut('CONFIRMED')
            ->setBookedAt(new \DateTimeImmutable());
        
        // Update stats 
        $counter->incConfirmed(1);

        // Persist and commit.
        $em->persist($reservation);

        $em->flush();

        $this->addFlash('success', 'Votre réservation est confirmée !');

        return $this->redirectToRoute('app_profile_cours');
    }

    /**
     * Cancel an existing reservation of the current user.
     *
     * POST /reservation/{id}/annuler
     */
    #[Route('/reservation/{id}/annuler', name: 'app_reservation_annuler', methods: ['POST'])]
    public function annulerReservation(StatsCounter $counter, int $id, Request $request, ReservationRepository $reservation_repository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var Reservation|null $reservation */
        $reservation = $reservation_repository->find($id);
        if (!$reservation) {
            $this->addFlash('error', 'Réservation introuvable.');
            return $this->redirectToRoute('app_profile_cours');
        }

        // CSRF protection
        if (!$this->isCsrfTokenValid('cancel_reservation' . $reservation->getId(), $request->request->get('_token'))) {
        $this->addFlash('error', 'Token invalide.');
        return $this->redirectToRoute('app_profile_cours');
        }

        // Ownership check: only the student who booked can cancel their reservation.
        if ($reservation->getStudent() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
        }

        // Set status
        $reservation->setStatut('CANCELLED');
        $reservation->setCancelledAt(new \DateTimeImmutable('now'));
        $reservation->setCancelledBy($this->getUser());
        $reservation->setUpdatedAt(new \DateTimeImmutable('now'));

        // Update stats
        $counter->incCancelled(1);

        // Commit changes.
        $em->flush();

        $this->addFlash('success', 'Votre réservation a bien été annulée.');

        return $this->redirectToRoute('app_profile_cours');
    }



}
