<?php

namespace App\Tests\Controller;

use App\Exception\Auth\InvalidResetTokenException;
use App\Service\PasswordResetService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PasswordResetControllerTest extends WebTestCase
{
    public function testForgotReturnsBadRequestWhenEmailMissing(): void
    {
        $client = static::createClient();
        $this->mockPasswordResetService();

        $client->request('POST', '/password/forgot', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode([])
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testForgotReturns200WhenEmailProvided(): void
    {
        $client = static::createClient();
        $stub   = $this->mockPasswordResetService();
        $stub->method('requestReset');

        $client->request('POST', '/password/forgot', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'user@example.com'])
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $data);
    }

    public function testForgotReturns200EvenWhenServiceThrows(): void
    {
        $client = static::createClient();
        $stub   = $this->mockPasswordResetService();
        $stub->method('requestReset')->willThrowException(new \Exception('fail'));

        $client->request('POST', '/password/forgot', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'unknown@example.com'])
        );

        $this->assertResponseIsSuccessful();
    }

    public function testResetReturnsBadRequestWhenFieldsMissing(): void
    {
        $client = static::createClient();
        $this->mockPasswordResetService();

        $client->request('POST', '/password/reset', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['token' => 'abc'])
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testResetReturnsBadRequestForShortPassword(): void
    {
        $client = static::createClient();
        $stub   = $this->mockPasswordResetService();
        $stub->method('resetPassword')->willThrowException(new \InvalidArgumentException('Password must be at least 8 characters'));

        $client->request('POST', '/password/reset', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['token' => 'sometoken', 'password' => 'short'])
        );

        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testResetReturnsBadRequestForInvalidToken(): void
    {
        $client = static::createClient();
        $stub   = $this->mockPasswordResetService();
        $stub->method('resetPassword')->willThrowException(new InvalidResetTokenException());

        $client->request('POST', '/password/reset', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['token' => 'badtoken', 'password' => 'newpass123'])
        );

        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testResetReturns200OnSuccess(): void
    {
        $client = static::createClient();
        $stub   = $this->mockPasswordResetService();
        $stub->method('resetPassword');

        $client->request('POST', '/password/reset', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['token' => 'validtoken', 'password' => 'newpass123'])
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $data);
    }

    private function mockPasswordResetService(): MockObject&PasswordResetService
    {
        $stub = $this->createMock(PasswordResetService::class);
        static::getContainer()->set(PasswordResetService::class, $stub);
        return $stub;
    }
}
