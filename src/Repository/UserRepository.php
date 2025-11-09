<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * UserRepository
 * -----------------------------------------------------------------------------
 * Purpose:
 *   Centralize all User entity queries and password-upgrade logic.
 */
/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

     /**
     * Find users having a given role.
     */
    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%"'.$role.'"%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

     /**
     * Find "students only":
     *   - Users that are plain ROLE_USER (or with empty roles),
     *   - and do NOT have ROLE_TEACHER nor ROLE_ADMIN.
     */
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
