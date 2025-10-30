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
        ->andWhere('s.cancelledAt IS NULL')
        ->andWhere('s.status = :status')
        ->andWhere('s.endAt >= :now')
        ->setParameter('teacher', $teacher)
        ->setParameter('status', 'SCHEDULED')
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
