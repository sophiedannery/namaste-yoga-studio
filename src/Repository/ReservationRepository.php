<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Session;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ReservationRepository
 * -----------------------------------------------------------------------------
 * Purpose:
 *   Encapsulate all query logic related to reservations:
 *   - upcoming/past reservations for a student,
 *   - active reservations for a given session,
 *   - counts used for capacity checks.
 */

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

     /**
     * Return all FUTURE reservations for a given student.
     */
    public function findUpcomingByStudent(User $student): array
    {
        $now = new \DateTimeImmutable('now');

        return $this->createQueryBuilder('r')
        ->innerJoin('r.session', 's')->addSelect('s')
        ->innerJoin('s.classType', 'ct')->addSelect('ct')
        ->leftJoin('s.teacher', 't')->addSelect('t')
        ->leftJoin('s.room', 'room')->addSelect('room')
        ->andWhere('r.student = :student')
        ->andWhere('s.startAt > :now')
        ->setParameter('student', $student)
        ->setParameter('now', $now)
        ->orderBy('s.startAt', 'ASC')
        ->getQuery()
        ->getResult();
    }


    /**
     * Return all PAST reservations for a given student.
     */
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

    /**
     * Count ACTIVE (confirmed & not cancelled) reservations for a session.
     */
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

    /**
     * Return ACTIVE reservations (confirmed & not cancelled) for a session,
     * including the enrolled students (joined and selected).
     */
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
    
}
