<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Session;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findUpcomingByStudent(User $student): array
    {
        $now = new \DateTimeImmutable('now');

        return $this->createQueryBuilder('r')
        ->innerJoin('r.session', 's')->addSelect('s')
        ->innerJoin('s.classType', 'ct')->addSelect('ct')
        ->leftJoin('s.teacher', 't')->addSelect('t')
        ->leftJoin('s.room', 'room')->addSelect('room')
        ->andWhere('r.student = :student')
        // ->andWhere('r.cancelledAt IS NULL')
        // ->andWhere('r.statut = :status')
        // ->andWhere('s.status = :sstatus')
        // ->andWhere('s.cancelledAt IS NULL')
        ->andWhere('s.startAt > :now')
        ->setParameter('student', $student)
        // ->setParameter('status', 'CONFIRMED')
        // ->setParameter('sstatus', 'SCHEDULED')
        ->setParameter('now', $now)
        ->orderBy('s.startAt', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function findPastByStudent(User $student): array 
    {
        $now = new \DateTimeImmutable('now'); 

    return $this->createQueryBuilder('r')
        ->innerJoin('r.session', 's')->addSelect('s')
        ->innerJoin('s.classType', 'ct')->addSelect('ct')
        ->leftJoin('s.teacher', 't')->addSelect('t')
        ->leftJoin('s.room', 'room')->addSelect('room')
        ->andWhere('r.student = :student')
        ->andWhere('r.statut = :status')          
        ->andWhere('s.startAt < :now')            
        ->setParameter('student', $student)
        ->setParameter('status', 'CONFIRMED')
        ->setParameter('now', $now)
        ->orderBy('s.startAt', 'DESC')            
        ->getQuery()
        ->getResult();
    }

    public function countActiveBySession(Session $session): int 
    {
        return (int) $this->createQueryBuilder('r')
        ->select('COUNT(r.id)')
        ->andWhere('r.session = :s')
        ->andWhere('r.statut = :status')
        ->andWhere('r.cancelledAt IS NULL')
        ->setParameter('s', $session)
        ->setParameter('status', 'CONFIRMED')
        ->getQuery()
        ->getSingleScalarResult();
    }

    public function findActiveBySession(Session $session, string $status = 'CONFIRMED'): array 
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.student', 'u')->addSelect('u')
            ->andWhere('r.session = :session')
            ->andWhere('r.cancelledAt IS NULL')
            ->andWhere('r.statut = :status')
            ->setParameter('session', $session)
            ->setParameter('status', $status)
            ->orderBy('u.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }
    

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
