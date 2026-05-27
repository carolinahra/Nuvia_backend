<?php

namespace App\Tests\Controller;

use App\Controller\UserWeightLogController;
use App\Service\UserWeightLogService;
use PHPUnit\Framework\MockObject\Stub;

class UserWeightLogControllerTest extends AbstractControllerTestCase
{
    private UserWeightLogController $controller;
    private UserWeightLogService&Stub $weightLogService;

    protected function setUp(): void
    {
        $this->weightLogService = $this->createStub(UserWeightLogService::class);
        $this->controller       = new UserWeightLogController($this->weightLogService);
        $this->setUpContainer($this->controller);
    }

    // --- list ---

    public function testListReturns200WithLogs(): void
    {
        $user = $this->makeUser(1);
        $log  = $this->makeWeightLog(1, $user, 72.5);

        $this->weightLogService->method('findByUser')->willReturn([$log]);

        $request  = $this->makeRequest(null, [], $user);
        $response = $this->controller->list($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(1, $data);
        $this->assertSame(72.5, $data[0]['weightKg']);
    }

    public function testListReturns200WithEmptyArray(): void
    {
        $user = $this->makeUser(1);
        $this->weightLogService->method('findByUser')->willReturn([]);

        $request  = $this->makeRequest(null, [], $user);
        $response = $this->controller->list($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([], $data);
    }

    // --- create ---

    public function testCreateReturns400WhenInvalidData(): void
    {
        $user = $this->makeUser(1);
        $this->weightLogService->method('create')->willThrowException(new \InvalidArgumentException('Weight required'));

        $request  = $this->makeRequest(json_encode([]), [], $user);
        $response = $this->controller->create($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    public function testCreateReturns201OnSuccess(): void
    {
        $user = $this->makeUser(1);
        $log  = $this->makeWeightLog(5, $user, 71.0);

        $this->weightLogService->method('create')->willReturn($log);

        $request  = $this->makeRequest(json_encode(['weightKg' => 71.0]), [], $user);
        $response = $this->controller->create($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame(5, $data['id']);
        $this->assertEquals(71.0, $data['weightKg']);
    }
}
