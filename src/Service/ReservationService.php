<?php 

namespace App\Service;

use App\Entity\Session;
use App\Entity\User;
use App\Repository\ReservationRepository;

final class ReservationService
{
    public function __construct(
        private ReservationRepository $reservationRepository,
    ) {}


    public function validateCanReserve(Session $session, User $user): array
    {

        $errors = [];

        //prof ne réserve pas son propre cours
        if($session->getTeacher() === $user) {
            $errors[] = 'Vous ne pouvez pas participer à votre propre cours.';
        }

        //pas de réservation après le début du cours
        if ((new \DateTimeImmutable()) >= $session->getStartAt() ) {
            $errors[] = 'Le cours a commencé : réservation impossible.';
        }

        //pas de doublons 
        $exists = $this->reservationRepository->findOneBy([
            'session' => $session,
            'student' => $user, 
        ]);

        if($exists && $exists->getCancelledAt() === null) {
            $errors[] = 'Vous participez déjà à ce cours.';
        }

        //capacité
        $remaining = $this->getRemainingPlaces($session);
        if ($remaining <= 0) {
            $errors[] = 'Plus de place disponible sur ce cours.';
        }

        return $errors;

    }

    public function getRemainingPlaces(Session $session): int
    {
        $active = $this->reservationRepository->countActiveBySession($session);
        return max(0, $session->getCapacity() - $active);
    }
}