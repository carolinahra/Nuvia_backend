<?php

namespace App\Tests\Controller;

use App\Controller\UserController;
use App\Service\DietService;
use App\Service\UserService;
use App\Service\UserWeightLogService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use PHPUnit\Framework\MockObject\Stub;

class UserControllerTest extends AbstractControllerTestCase
{
    private UserController $controller;
    private UserService&Stub $userService;
    private UserWeightLogService&Stub $weightLogService;
    private DietService&Stub $dietService;

    protected function setUp(): void
    {
        $this->userService      = $this->createStub(UserService::class);
        $this->weightLogService = $this->createStub(UserWeightLogService::class);
        $this->dietService      = $this->createStub(DietService::class);
        $this->controller       = new UserController($this->userService, $this->weightLogService, $this->dietService);
        $this->setUpContainer($this->controller);
    }

    // --- register ---

    public function testRegisterReturns400WhenInvalidData(): void
    {
        $this->userService->method('create')->willThrowException(new \InvalidArgumentException('Email required'));

        $request  = $this->makeRequest(json_encode([]));
        $response = $this->controller->register($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    public function testRegisterReturns409WhenDuplicate(): void
    {
        $duplicateEx = $this->createStub(UniqueConstraintViolationException::class);
        $this->userService->method('create')->willThrowException($duplicateEx);

        $request  = $this->makeRequest(json_encode(['email' => 'dup@example.com', 'username' => 'dup']));
        $response = $this->controller->register($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(409, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    public function testRegisterReturns201OnSuccess(): void
    {
        $user = $this->makeUser(42);
        $this->userService->method('create')->willReturn($user);

        $request  = $this->makeRequest(json_encode([
            'name'            => 'Test',
            'username'        => 'testuser',
            'email'           => 'test@example.com',
            'password'        => 'password123',
            'currentWeightKg' => 70.0,
        ]));
        $response = $this->controller->register($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame(42, $data['id']);
    }

    // --- update ---

    public function testUpdateReturns400WhenInvalidData(): void
    {
        $user = $this->makeUser(1);
        $this->userService->method('update')->willThrowException(new \InvalidArgumentException('Invalid field'));

        $request  = $this->makeRequest(json_encode(['heightCm' => -1]), [], $user);
        $response = $this->controller->update($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    public function testUpdateReturns200OnSuccess(): void
    {
        $user = $this->makeUser(1);
        $user->setName('Updated')->setUsername('updated')->setEmail('updated@example.com');

        $request  = $this->makeRequest(json_encode(['name' => 'Updated']), [], $user);
        $response = $this->controller->update($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Updated', $data['name']);
        $this->assertSame(1, $data['id']);
    }
}
