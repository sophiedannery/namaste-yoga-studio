<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\AdminService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdminServiceTest extends TestCase
{
    public function testCreateTeacherThrowsWhenPasswordMissing(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        $service = new AdminService($em, $hasher);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Le mot de passe est obligatoire.');

        $service->createTeacher(new User(), null);
    }

    public function testCreateTeacherHashesPasswordSetsRoleAndPersists(): void
    {
        $user = new User();
        $plainPassword = 'StrongPassword123!';
        $hashedPassword = 'HASHED_VALUE';

        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        // hashPassword doit être appelé avec (User, plainPassword) et retourner un hash.
        $hasher
            ->expects($this->once())
            ->method('hashPassword')
            ->with($this->identicalTo($user), $this->equalTo($plainPassword))
            ->willReturn($hashedPassword);

        // persist puis flush doivent être appelés
        $em
            ->expects($this->once())
            ->method('persist')
            ->with($this->identicalTo($user));

        $em
            ->expects($this->once())
            ->method('flush');

        $service = new AdminService($em, $hasher);

        $result = $service->createTeacher($user, $plainPassword);

        $this->assertSame($user, $result, 'La méthode doit retourner le même User.');
        $this->assertSame($hashedPassword, $user->getPassword(), 'Le mot de passe doit être hashé et stocké.');
        $this->assertContains('ROLE_TEACHER', $user->getRoles(), 'Le rôle ROLE_TEACHER doit être présent.');
    }

    public function testDeleteTeacherThrowsIfUserIsNotTeacher(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']); // pas prof

        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        // On vérifie qu'on ne supprime pas et qu'on ne flush pas si exception
        $em->expects($this->never())->method('remove');
        $em->expects($this->never())->method('flush');

        $service = new AdminService($em, $hasher);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Cet utilisateur n’est pas un professeur.');

        $service->deleteTeacher($user);
    }

    public function testDeleteTeacherRemovesAndFlushesIfTeacher(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_TEACHER']);

        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        $em
            ->expects($this->once())
            ->method('remove')
            ->with($this->identicalTo($user));

        $em
            ->expects($this->once())
            ->method('flush');

        $service = new AdminService($em, $hasher);

        $service->deleteTeacher($user);

        $this->assertTrue(true, 'Pas d’exception = OK');
    }

    public function testDeleteStudentRemovesAndFlushes(): void
    {
        $user = new User();

        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        $em
            ->expects($this->once())
            ->method('remove')
            ->with($this->identicalTo($user));

        $em
            ->expects($this->once())
            ->method('flush');

        $service = new AdminService($em, $hasher);

        $service->deleteStudent($user);

        $this->assertTrue(true, 'Pas d’exception = OK');
    }
}
