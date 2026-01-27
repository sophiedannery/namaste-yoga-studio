<?php 

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Session;
use App\Entity\User;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ReservationService
{
    public function __construct(
        private ReservationRepository $reservationRepository,
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
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

        //verification place dispo
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


    public function reserve(Session $session, User $user): array 
    {
        $errors = $this->validateCanReserve($session, $user);
        if (!empty($errors)) {
            return ['reservation' => null, 'errors' => $errors];
        }

        $reservation = (new Reservation())
            ->setSession($session)
            ->setStudent($user)
            ->setStatut('CONFIRMED')
            ->setBookedAt(new \DateTimeImmutable());

        $violations = $this->validator->validate($reservation);
        if (count($violations) > 0) {
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }
            return ['reservation' => null, 'errors' => $errors];
        }

        $this->em->persist($reservation);
        $this->em->flush();

        return ['reservation' => $reservation, 'errors' => []];

    }
}