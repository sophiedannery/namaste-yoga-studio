<?php

namespace App\Tests\Service;

use App\Entity\Session;
use App\Entity\User;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ReservationServiceTest extends TestCase
{
    public function testGetRemainingPlacesReturnsCapacityMinusActive(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $repo = $this->createMock(ReservationRepository::class);
        $repo->method('countActiveBySession')->willReturn(3);

        $service = new ReservationService($repo, $em, $validator);

        $session = $this->createMock(Session::class);
        $session->method('getCapacity')->willReturn(10);

        $remaining = $service->getRemainingPlaces($session);

        $this->assertSame(7, $remaining);
    
    }

    public function testValidateCanReserveReturnsErrorWhenNoPlacesLeft(): void 
    {

    $em = $this->createMock(EntityManagerInterface::class);
    $validator = $this->createMock(ValidatorInterface::class);
    $repo = $this->createMock(ReservationRepository::class);
    $repo->method('countActiveBySession')->willReturn(10); //complet

    $repo->method('findOneBy')->willReturn(null);

    $service = new ReservationService($repo, $em, $validator);

    $user = $this->createMock(User::class);

    $session = $this->createMock(Session::class);
    $session->method('getCapacity')->willReturn(10);
    $session->method('getTeacher')->willReturn($this->createMock(User::class));
    $session->method('getStartAt')->willReturn(new \DateTimeImmutable('+1 day')); // pas commencé

    $errors = $service->validateCanReserve($session, $user);
    $this->assertContains('Plus de place disponible sur ce cours.', $errors);

    }

    public function testValidateCanReserveReturnsErrorWhenTeacherBooksOwnSession(): void 
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $repo = $this->createMock(ReservationRepository::class);
        $repo->method('findOneBy')->willReturn(null);
        $repo->method('countActiveBySession')->willReturn(0);
        
        $service = new ReservationService($repo, $em, $validator);
        
        $user = $this->createMock(User::class);

        $session = $this->createMock(Session::class);
        $session->method('getTeacher')->willReturn($user); //user = prof
        $session->method('getStartAt')->willReturn(new \DateTimeImmutable('+1 day'));
        $session->method('getCapacity')->willReturn(10);

        $errors = $service->validateCanReserve($session, $user);
        $this->assertContains('Vous ne pouvez pas participer à votre propre cours.', $errors);
    }

    public function testValidateCanReserveReturnsNoErrorWhenEverythingIsOk(): void 
    {
        $em = $this->createMock(EntityManagerInterface::class);
    $validator = $this->createMock(ValidatorInterface::class);
        $repo = $this->createMock(ReservationRepository::class);
        $repo->method('findOneBy')->willReturn(null); //verif pas de doublon
        $repo->method('countActiveBySession')->willReturn(2);

        $service = new ReservationService($repo, $em, $validator);

        $user = $this->createMock(User::class);

        $session = $this->createMock(Session::class);
        $session->method('getTeacher')->willReturn($this->createMock(User::class));
        $session->method('getStartAt')->willReturn(new \DateTimeImmutable('+1 day'));
        $session->method('getCapacity')->willReturn(10);

        $errors = $service->validateCanReserve($session, $user);
        $this->assertSame([], $errors);

    }

    public function testValidateCanReserveReturnsErrorWhenSessionHasAlreadyStarted(): void 
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $repo = $this->createMock(ReservationRepository::class);
        $repo->method('findOneBy')->willReturn(null);          // pas de doublon
        $repo->method('countActiveBySession')->willReturn(0);

        $service = new ReservationService($repo, $em, $validator);

        $user = $this->createMock(User::class);

        $session = $this->createMock(Session::class);
        $session->method('getTeacher')->willReturn($this->createMock(User::class)); // prof != user
        $session->method('getCapacity')->willReturn(10);

        //le cours a déjà commencé 
        $session->method('getStartAt')->willReturn(new \DateTimeImmutable('-1 hour'));

        $errors = $service->validateCanReserve($session, $user);
        $this->assertContains('Le cours a commencé : réservation impossible.', $errors);
    }





}