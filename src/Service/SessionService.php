<?php
namespace App\Service;

use App\Entity\Session;
use App\Entity\User;
use App\Stats\StatsCounter;
use Doctrine\ORM\EntityManagerInterface;

final class SessionService
{
    public function __construct(
        private EntityManagerInterface $em,
        private StatsCounter $counter,
    )
    {}

    public function prepareNewSession(Session $session, User $teacher): void 
    {
        $session->setTeacher($teacher);
    }

    public function create(Session $session): void 
    {
        $session->setStatus('SCHEDULED');
        $session->setUpdatedAt(new \DateTimeImmutable());

        $this->counter->incCreated(1);

        $this->em->persist($session);
        $this->em->flush();

    }


}