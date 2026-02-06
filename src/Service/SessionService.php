<?php
namespace App\Service;

use App\Entity\Session;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class SessionService
{
    // Injection des dépendances
    public function __construct(
        private EntityManagerInterface $em
    )
    {}

    // =========================
    // Associer professeur à la session
    // =========================
    public function setTeacherNewSession(Session $session, User $teacher): void 
    {
        $session->setTeacher($teacher);
    }

    // =========================
    // Créer une session
    // =========================
    public function create(Session $session): void 
    {
        // Statut par défaut
        $session->setStatus('SCHEDULED');
        // Mise à jour de la date de modification
        $session->setUpdatedAt(new \DateTimeImmutable());

        // Enregistrement en base
        $this->em->persist($session);
        $this->em->flush();
    }

    // =========================
    // Annuler une session (changement de statut)
    // =========================
    public function cancel(Session $session, User $teacher): array
    {
        $errors = [];

        // Seul le professeur propriétaire du cours peut annuler
        if ($session->getTeacher() !== $teacher) {
            $errors[] = 'Accès interdit';
            return ['errors' => $errors];
        }

        // Empêcher l’annulation si la session est déjà annulée
        if ($session->getStatus() === 'CANCELLED') {
            $errors[] = 'Session déjà annulée.';
            return ['errors' => $errors];
        }

        // Annulation logique : on change le statut
        $session->setStatus('CANCELLED');
        $session->setCancelledAt(new \DateTimeImmutable());
        $session->setCancelledBy($teacher);

        // Sauvegarde des modifications
        $this->em->flush();

        // Retour succès
        return ['errors' => []];
    }
}