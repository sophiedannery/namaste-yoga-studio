<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdminService
{
    // Injection des dépendances
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    // =========================
    // Admin : création d'un professeur
    // =========================
    public function createTeacher(User $user, ?string $plainPassword): User 
    {
        // Vérifie que le mot de passe n'est pas vide
        if (!$plainPassword) {
            throw new \InvalidArgumentException('Le mot de passe est obligatoire.');
        }

        // Hash du mot de passe
        $hashed = $this->passwordHasher->hashPassword($user, $plainPassword);
        
        // Définir le mot de passe et le rôle
        $user->setPassword($hashed);
        $user->setRoles(['ROLE_TEACHER']);

        // Enregistrer l'utilisateur en base
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Retourner l’utilisateur créé
        return $user;
    }

    // =========================
    // Admin : suppression d'un professeur
    // =========================
    public function deleteTeacher(User $user): void 
    {
        // Vérifie le ROLE_TEACHER
        if (!in_array('ROLE_TEACHER', $user->getRoles(), true)) {
            throw new \LogicException('Cet utilisateur n’est pas un professeur.');
        }

        // Suppression en base
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    // =========================
    // Admin : suppression d'un élève
    // =========================
    public function deleteStudent(User $user): void
    {
        // Suppression de l'élève en base
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }





}