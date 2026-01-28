<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\RegistrationService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationServiceTest extends TestCase
{
    public function testRegisterThrowsWhenPasswordEmpty(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        $service = new RegistrationService($em, $hasher);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Le mot de passe est obligatoire.');

        $service->register(new User(), '');
    }

    public function testRegisterSetsEverythingAndPersists(): void
    {
        $user = new User();
        $user->setEmail('TesT@Example.COM');

        $plainPassword = 'StrongPassword123!';
        $hashedPassword = 'HASHED_VALUE';

        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        $hasher
            ->expects($this->once())
            ->method('hashPassword')
            ->with($this->identicalTo($user), $this->equalTo($plainPassword))
            ->willReturn($hashedPassword);

        $em
            ->expects($this->once())
            ->method('persist')
            ->with($this->identicalTo($user));

        $em
            ->expects($this->once())
            ->method('flush');

        $service = new RegistrationService($em, $hasher);

        $result = $service->register($user, $plainPassword);

        $this->assertSame($user, $result);
        $this->assertSame($hashedPassword, $user->getPassword());

        // Attention: getRoles() ajoute toujours ROLE_USER automatiquement
        $this->assertContains('ROLE_USER', $user->getRoles());

        $this->assertSame('test@example.com', $user->getEmail());

        // ✅ Ton getter
        $this->assertTrue($user->isActive());
    }
}
