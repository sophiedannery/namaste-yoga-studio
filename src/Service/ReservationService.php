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
    // Injection des dépendances
    public function __construct(
        private ReservationRepository $reservationRepository,
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
    ) {}

    // =========================
    // Vérification des règles métier avant de réserver
    // =========================
    public function validateCanReserve(Session $session, User $user): array
    {
        $errors = [];

        // Interdire au professeur de réserver son propre cours
        if($session->getTeacher() === $user) {
            $errors[] = 'Vous ne pouvez pas participer à votre propre cours.';
        }

        // Interdire la réservation si le cours a déjà commencé
        if ((new \DateTimeImmutable()) >= $session->getStartAt() ) {
            $errors[] = 'Le cours a commencé : réservation impossible.';
        }

        // Empêcher les doublons de réservation (même élève + même session)
        $exists = $this->reservationRepository->findOneBy([
            'session' => $session,
            'student' => $user, 
        ]);

        // Si une réservation existe et n'a pas été annulée, on bloque
        if($exists && $exists->getCancelledAt() === null) {
            $errors[] = 'Vous participez déjà à ce cours.';
        }

        // Vérifier qu'il reste des places
        $remaining = $this->getRemainingPlaces($session);
        if ($remaining <= 0) {
            $errors[] = 'Plus de place disponible sur ce cours.';
        }

        // Retourne toutes les erreurs trouvées
        return $errors;
    }


    // =========================
    // Calcule le nombre de places restantes pour une session
    // =========================
    public function getRemainingPlaces(Session $session): int
    {
        // Compte les réservations actives sur cette session
        $active = $this->reservationRepository->countActiveBySession($session);
        return max(0, $session->getCapacity() - $active);
    }


    // =========================
    // Crée une réservation 
    // =========================
    public function reserve(Session $session, User $user): array 
    {
        // Vérifier les règles métier avant création
        $errors = $this->validateCanReserve($session, $user);
        if (!empty($errors)) {
            return ['reservation' => null, 'errors' => $errors];
        }

        // Créer l'entité Reservation
        $reservation = (new Reservation())
            ->setSession($session)
            ->setStudent($user)
            ->setStatut('CONFIRMED')
            ->setBookedAt(new \DateTimeImmutable());

        // Validation des contraintes de l'entité
        $violations = $this->validator->validate($reservation);
        if (count($violations) > 0) {
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }
            return ['reservation' => null, 'errors' => $errors];
        }

        // Sauvegarde en base
        $this->em->persist($reservation);
        $this->em->flush();

        return ['reservation' => $reservation, 'errors' => []];
    }


    // =========================
    // Annule une réservation (changement de statut)
    // =========================
    public function cancel(Reservation $reservation, User $user): array
    {
        $errors = [];

        // Seul l'élève concerné peut annuler sa réservation
        if ($reservation->getStudent() !== $user) {
            $errors[] = 'Accès interdit';
            return ['errors' => $errors];
        }

        // Si la réservation est déjà annulée, on renvoie une erreur métier
        if ($reservation->getStatut() === 'CANCELLED') {
            $errors[] = 'Réservation déjà annulée.';
            return ['errors' => $errors];
        }

        // Date courante utilisée pour les champs de suivi
        $now = new \DateTimeImmutable();

        // Mise à jour des champs de la réservation
        $reservation->setStatut('CANCELLED');
        $reservation->setCancelledAt($now);
        $reservation->setUpdatedAt($now);
        $reservation->setCancelledBy($user);

        // Enregistrer les modifications en base
        $this->em->flush();

        // Retour succès
        return ['errors' => []];
    }
}