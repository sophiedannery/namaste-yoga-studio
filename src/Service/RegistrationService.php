<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationService
{
    // Injection des dépendances
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}


    // =========================
    // Inscription d’un nouvel utilisateur
    // =========================
    public function register(User $user, string $plainPassword): User
    {
        // Vérifie que le mot de passe n'est pas vide
        if ($plainPassword === '') {
            throw new \InvalidArgumentException('Le mot de passe est obligatoire.');
        }

        // Définir le mot de passe et le hasher
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
        // Définir le ROLE_USER
        $user->setRoles(['ROLE_USER']);
        // Normalisation de l’email (minuscules)
        $user->setEmail(mb_strtolower($user->getEmail() ?? ''));
        // Activation du compte utilisateur
        $user->setIsActive(true);

        // Enregistrement de l’utilisateur en base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Retourne l’utilisateur créé
        return $user;
    }

}