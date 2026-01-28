<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function register(User $user, string $plainPassword): User
    {
        if ($plainPassword === '') {
            throw new \InvalidArgumentException('Le mot de passe est obligatoire.');
        }

        // Hash password
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));

        // Default role
        $user->setRoles(['ROLE_USER']);

        // Normalize email
        $user->setEmail(mb_strtolower($user->getEmail() ?? ''));

        // Active account
        $user->setIsActive(true);

        // Persist
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

}