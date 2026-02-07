<?php

namespace App\Repository;

use App\Entity\Session;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    // =========================
    // Sessions à venir d’un professeur
    // =========================
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

    // =========================
    // Sessions passées d’un professeur
    // =========================
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
            ->orderBy('s.startAt', 'ACS')
            ->getQuery()
            ->getResult();
    }

    // =========================
    // Récupérer toutes les sessions
    // =========================
    public function findAll(): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.teacher', 't')->addSelect('t')
            ->leftJoin('s.classType', 'ct')->addSelect('ct')
            ->leftJoin('s.room', 'r')->addSelect('r')
            ->orderBy('s.startAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // =========================
    // Récupérer toutes les sessions à venir
    // =========================
    public function findAllUpcoming(): array
    {
        $now = new \DateTimeImmutable('now');

        return $this->createQueryBuilder('s')
            ->leftJoin('s.teacher', 't')->addSelect('t')
            ->leftJoin('s.classType', 'ct')->addSelect('ct')
            ->leftJoin('s.room', 'r')->addSelect('r')
            ->andWhere('s.startAt >= :now')
            ->andWhere('s.status = :status') // on n’affiche pas les annulés
            ->setParameter('now', $now)
            ->setParameter('status', 'SCHEDULED')
            ->orderBy('s.startAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // =========================
    // Sessions à venir avec filtres (niveau, professeur, style)
    // =========================
    public function findUpcomingByFilters(?string $level, ?string $teacher, ?string $style): array
    {
        $now = new \DateTimeImmutable('now');

        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.classType', 'ct')->addSelect('ct')
            ->leftJoin('s.teacher', 't')->addSelect('t')
            ->andWhere('s.startAt >= :now')
            ->andWhere('s.status = :status')
            ->setParameter('now', $now)
            ->setParameter('status', 'SCHEDULED')
            ->orderBy('s.startAt', 'ASC');

        // Filtre niveau
        if ($level !== null && $level !== '') {
            $qb->andWhere('LOWER(ct.level) = LOWER(:level)')
            ->setParameter('level', trim($level));
        }

        // Filtre style
        if ($style !== null && $style !== '') {
            $qb->andWhere('LOWER(ct.style) = LOWER(:style)')
            ->setParameter('style', trim($style));
        }

        // Filtre professeur
        if ($teacher !== null && $teacher !== '') {
            $qb->andWhere('LOWER(t.firstName) = LOWER(:teacher)')
            ->setParameter('teacher', trim($teacher));
        }

        return $qb->getQuery()->getResult();
    }
}
