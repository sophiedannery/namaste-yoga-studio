<?php

namespace App\Repository;

use App\Entity\Session;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function findUpcomingByTeacher(User $teacher): array 
    {
        $now = new \DateTimeImmutable('now');

        return $this->createQueryBuilder('s')
        ->andWhere('s.teacher = :teacher')
        // ->andWhere('s.cancelledAt IS NULL')
        // ->andWhere('s.status = :status')
        ->andWhere('s.endAt >= :now')
        ->setParameter('teacher', $teacher)
        // ->setParameter('status', 'SCHEDULED')
        ->setParameter('now', $now)
        ->leftJoin('s.classType', 'ct')->addSelect('ct')
        ->leftJoin('s.room', 'room')->addSelect('room')
        ->leftJoin('s.reservations', 'r')->addSelect('r')
        ->orderBy('s.startAt', 'ASC')
        ->getQuery()
        ->getResult();
    }

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



    // public function findByLevel(string $level): array 
    // {
    //     return $this->createQueryBuilder('s')
    //         ->join('s.classType', 'ct')
    //         ->addSelect('ct')
    //         ->andWhere('ct.level = :lvl')
    //         ->setParameter('lvl', $level)
    //         ->getQuery()
    //         ->getResult();
    // }

    // public function findByTeacher(string $teacher): array
    // {
    //     return $this->createQueryBuilder('s')
    //         ->join('s.user', 'u')
    //         ->addSelect('u')
    //         ->andWhere('u.firstName = :tch')
    //         ->setParameter('tch', $teacher)
    //         ->getQuery()
    //         ->getResult();
    // }




    //    /**
    //     * @return Session[] Returns an array of Session objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Session
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
