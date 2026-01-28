<?php

namespace App\Tests\Service;

use App\Entity\Session;
use App\Entity\User;
use App\Service\SessionService;
use App\Stats\StatsCounter;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class SessionServiceTest extends TestCase
{
    public function testPrepareNewSessionSetsTeacher(): void
    {
        // Arrange
        $em = $this->createMock(EntityManagerInterface::class);
        $counter = $this->createMock(StatsCounter::class);

        $service = new SessionService($em, $counter);

        $session = $this->createMock(Session::class);
        $teacher = $this->createMock(User::class);

        // On vérifie que setTeacher est appelé avec $teacher
        $session->expects($this->once())
            ->method('setTeacher')
            ->with($teacher);

        // Act
        $service->prepareNewSession($session, $teacher);

        // Assert : géré par expects()
        $this->assertTrue(true);
    }

    public function testCreateSetsDefaultsAndPersistsAndUpdatesStats(): void
    {
        // Arrange
        $em = $this->createMock(EntityManagerInterface::class);
        $counter = $this->createMock(StatsCounter::class);

        $service = new SessionService($em, $counter);

        $session = $this->createMock(Session::class);

        // Vérifie les valeurs par défaut
        $session->expects($this->once())
            ->method('setStatus')
            ->with('SCHEDULED')
            ->willReturnSelf();

        // On ne vérifie pas la date exacte, juste que c'est un DateTimeImmutable
        $session->expects($this->once())
            ->method('setUpdatedAt')
            ->with($this->isInstanceOf(\DateTimeImmutable::class))
            ->willReturnSelf();

        // Vérifie incrément stats
        $counter->expects($this->once())
            ->method('incCreated')
            ->with(1);

        // Vérifie persist + flush
        $em->expects($this->once())
            ->method('persist')
            ->with($session);

        $em->expects($this->once())
            ->method('flush');

        // Act
        $service->create($session);

        // Assert : géré par expects()
        $this->assertTrue(true);
    }
}
