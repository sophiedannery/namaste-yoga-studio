<?php

namespace App\Repository;

use App\Entity\Session;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * SessionRepository
 * -----------------------------------------------------------------------------
 * Purpose:
 *   Encapsulate all query logic for yoga sessions (teacher's upcoming/past,
 *   public listings, and filter-based search).
 */
/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    /**
     * Find FUTURE (or ongoing) sessions for a specific teacher.
     */
    public function findUpcomingByTeacher(User $teacher): array 
    {
        $now = new \DateTimeImmutable('now');

        return $this->createQueryBuilder('s')
        ->andWhere('s.teacher = :teacher')
        ->andWhere('s.endAt >= :now')
        ->setParameter('teacher', $teacher)
        ->setParameter('now', $now)
        ->leftJoin('s.classType', 'ct')->addSelect('ct')
        ->leftJoin('s.room', 'room')->addSelect('room')
        ->leftJoin('s.reservations', 'r')->addSelect('r')
        ->orderBy('s.startAt', 'ASC')
        ->getQuery()
        ->getResult();
    }

    /**
     * Find PAST sessions for a specific teacher.
     */
    public function findPastByTeacher(User $teacher): array 
    {
        $now = new \DateTimeImmutable('now');

        return $this->createQueryBuilder('s')
            ->andWhere('s.teacher = :teacher')
            ->andWhere('s.endAt < :now')
            ->setParameter('teacher', $teacher)
            ->setParameter('now', $now)
            ->leftJoin('s.classType', 'ct')->addSelect('ct')
            ->leftJoin('s.room', 'room')->addSelect('room')
            ->leftJoin('s.reservations', 'r')->addSelect('r')
            ->orderBy('s.startAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Custom "findAll" including common relations and ordering.
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.teacher', 't')->addSelect('t')
            ->leftJoin('s.classType', 'ct')->addSelect('ct')
            ->leftJoin('s.room', 'r')->addSelect('r')
            ->orderBy('s.startAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Filter sessions for the public planning by level, teacher name, and style.
     */
    public function findByFilters(?string $level, ?string $teacher, ?string $style): array
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.classType', 'ct')->addSelect('ct')
            ->leftJoin('s.teacher', 't')->addSelect('t')
            ->orderBy('s.startAt', 'DESC');

        if ($level !== null && $level !== '') {
            $qb->andWhere('LOWER(ct.level) = LOWER(:level)')
                ->setParameter('level', trim($level));
        }

        if ($style !== null && $style !== '') {
            $qb->andWhere('LOWER(ct.style) = LOWER(:style)')
                ->setParameter('style', trim($style));
        }

        if ($teacher !== null && $teacher !== '') {
            $qb->andWhere('LOWER(t.firstName) = LOWER(:teacher)')
                ->setParameter('teacher', trim($teacher));
        }

        return $qb->getQuery()->getResult();
    }

}
