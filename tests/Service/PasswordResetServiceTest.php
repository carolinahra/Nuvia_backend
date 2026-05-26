<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Exception\Auth\InvalidResetTokenException;
use App\Repository\UserRepository;
use App\Service\EmailService;
use App\Service\PasswordResetService;
use PHPUnit\Framework\TestCase;

class PasswordResetServiceTest extends TestCase
{
    private UserRepository $repo;
    private EmailService $emailService;
    private PasswordResetService $service;

    protected function setUp(): void
    {
        $this->repo         = $this->createMock(UserRepository::class);
        $this->emailService = $this->createMock(EmailService::class);
        $this->service      = new PasswordResetService($this->repo, $this->emailService);
    }

    public function testRequestResetDoesNothingWhenEmailNotFound(): void
    {
        $this->repo->method('get')->willReturn(null);
        $this->emailService->expects($this->never())->method('send');
        $this->repo->expects($this->never())->method('save');

        $this->service->requestReset('notfound@example.com');
    }

    public function testRequestResetSavesTokenAndSendsEmail(): void
    {
        $user = new User();
        $user->setName('Test');
        $user->setUsername('testuser');
        $user->setEmail('user@example.com');
        $user->setPassword('hashed');

        $this->repo->method('get')->with(null, 'user@example.com')->willReturn($user);
        $this->repo->expects($this->once())->method('save')->with($user);
        $this->emailService->expects($this->once())->method('send');

        $this->service->requestReset('user@example.com');

        $this->assertNotNull($user->getResetToken());
        $this->assertNotNull($user->getResetTokenExpiresAt());
        $this->assertGreaterThan(new \DateTime(), $user->getResetTokenExpiresAt());
    }

    public function testResetPasswordThrowsForUnknownToken(): void
    {
        $this->repo->method('findByResetToken')->willReturn(null);

        $this->expectException(InvalidResetTokenException::class);
        $this->service->resetPassword('badtoken', 'newpassword123');
    }

    public function testResetPasswordThrowsForExpiredToken(): void
    {
        $user = new User();
        $user->setName('Test');
        $user->setUsername('testuser');
        $user->setEmail('user@example.com');
        $user->setPassword('hashed');
        $user->setResetToken(hash('sha256', 'sometoken'));
        $user->setResetTokenExpiresAt(new \DateTime('-1 hour'));

        $this->repo->method('findByResetToken')->willReturn($user);

        $this->expectException(InvalidResetTokenException::class);
        $this->service->resetPassword('sometoken', 'newpassword123');
    }

    public function testResetPasswordUpdatesPasswordAndClearsToken(): void
    {
        $user = new User();
        $user->setName('Test');
        $user->setUsername('testuser');
        $user->setEmail('user@example.com');
        $user->setPassword('oldhashed');
        $user->setResetToken(hash('sha256', 'validtoken'));
        $user->setResetTokenExpiresAt(new \DateTime('+15 minutes'));

        $this->repo->method('findByResetToken')
            ->with(hash('sha256', 'validtoken'))
            ->willReturn($user);
        $this->repo->expects($this->once())->method('save')->with($user);

        $this->service->resetPassword('validtoken', 'newpassword123');

        $this->assertTrue(password_verify('newpassword123', $user->getPassword()));
        $this->assertNull($user->getResetToken());
        $this->assertNull($user->getResetTokenExpiresAt());
    }

    public function testResetPasswordThrowsForShortPassword(): void
    {
        $user = new User();
        $user->setName('Test');
        $user->setUsername('testuser');
        $user->setEmail('user@example.com');
        $user->setPassword('hashed');
        $user->setResetToken(hash('sha256', 'validtoken'));
        $user->setResetTokenExpiresAt(new \DateTime('+15 minutes'));

        $this->repo->method('findByResetToken')->willReturn($user);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 8 characters');
        $this->service->resetPassword('validtoken', 'short');
    }
}
