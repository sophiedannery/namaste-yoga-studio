<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdminService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    // Admin : création d'un Teacher
    public function createTeacher(User $user, ?string $plainPassword): User 
    {

    if (!$plainPassword) {
        throw new \InvalidArgumentException('Le mot de passe est obligatoire.');
    }

    $hashed = $this->passwordHasher->hashPassword($user, $plainPassword);
    $user->setPassword($hashed);

    $user->setRoles(['ROLE_TEACHER']);

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    return $user;
    }

    // Admin : suppression d'un Teacher
    public function deleteTeacher(User $user): void 
    {
        if (!in_array('ROLE_TEACHER', $user->getRoles(), true)) {
            throw new \LogicException('Cet utilisateur n’est pas un professeur.');
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    // Admin : suppresion d'un Student
    public function deleteStudent(User $user): void 
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }





}