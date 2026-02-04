<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // =========================
    // Mise à jour du mot de passe (rehash automatique)
    // =========================
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    // =========================
    // Récupérer les utilisateurs par rôle
    // =========================
    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%"'.$role.'"%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // =========================
    // Récupérer uniquement les élèves
    // =========================
    public function findStudentsOnly(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('(u.roles LIKE :user OR u.roles = :empty)')
            ->andWhere('u.roles NOT LIKE :teacher')
            ->andWhere('u.roles NOT LIKE :admin')
            ->setParameter('user', '%"ROLE_USER"%')
            ->setParameter('empty', '[]')
            ->setParameter('teacher', '%"ROLE_TEACHER"%')
            ->setParameter('admin', '%"ROLE_ADMIN"%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
