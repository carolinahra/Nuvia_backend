<?php

namespace App\Tests\Controller;

use App\Controller\TrainingSessionController;
use App\Service\RoutineService;
use App\Service\TrainingSessionService;
use PHPUnit\Framework\MockObject\Stub;

class TrainingSessionControllerTest extends AbstractControllerTestCase
{
    private TrainingSessionController $controller;
    private TrainingSessionService&Stub $sessionService;
    private RoutineService&Stub $routineService;

    protected function setUp(): void
    {
        $this->sessionService = $this->createStub(TrainingSessionService::class);
        $this->routineService = $this->createStub(RoutineService::class);
        $this->controller     = new TrainingSessionController($this->sessionService, $this->routineService);
        $this->setUpContainer($this->controller);
    }

    // --- list ---

    public function testListReturns200WithSessions(): void
    {
        $user    = $this->makeUser(1);
        $routine = $this->makeRoutine(1, 'Push Day');
        $session = $this->makeTrainingSession(10, $user, $routine);

        $this->sessionService->method('findByUser')->willReturn([$session]);

        $request  = $this->makeRequest(null, [], $user);
        $response = $this->controller->list($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(1, $data);
        $this->assertSame(10, $data[0]['id']);
    }

    public function testListReturns200WithEmptyArray(): void
    {
        $user = $this->makeUser(1);
        $this->sessionService->method('findByUser')->willReturn([]);

        $request  = $this->makeRequest(null, [], $user);
        $response = $this->controller->list($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([], $data);
    }

    // --- get ---

    public function testGetReturns200WhenFoundAndOwned(): void
    {
        $user    = $this->makeUser(1);
        $routine = $this->makeRoutine(1);
        $session = $this->makeTrainingSession(10, $user, $routine);

        $this->sessionService->method('findOne')->willReturn($session);

        $request  = $this->makeRequest(null, [], $user);
        $response = $this->controller->get(10, $request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(10, $data['id']);
    }

    public function testGetReturns404WhenNotFound(): void
    {
        $user = $this->makeUser(1);
        $this->sessionService->method('findOne')->willReturn(null);

        $request  = $this->makeRequest(null, [], $user);
        $response = $this->controller->get(99, $request);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testGetReturns404WhenSessionBelongsToAnotherUser(): void
    {
        $owner   = $this->makeUser(2);
        $caller  = $this->makeUser(1);
        $routine = $this->makeRoutine(1);
        $session = $this->makeTrainingSession(10, $owner, $routine);

        $this->sessionService->method('findOne')->willReturn($session);

        $request  = $this->makeRequest(null, [], $caller);
        $response = $this->controller->get(10, $request);

        $this->assertSame(404, $response->getStatusCode());
    }

    // --- create ---

    public function testCreateReturns404WhenRoutineNotFound(): void
    {
        $user = $this->makeUser(1);
        $this->routineService->method('findOne')->willReturn(null);

        $request  = $this->makeRequest(json_encode(['routineId' => 99]), [], $user);
        $response = $this->controller->create($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    public function testCreateReturns400WhenInvalidArgument(): void
    {
        $user    = $this->makeUser(1);
        $routine = $this->makeRoutine(1);
        $this->routineService->method('findOne')->willReturn($routine);
        $this->sessionService->method('create')->willThrowException(new \InvalidArgumentException('Bad data'));

        $request  = $this->makeRequest(json_encode(['routineId' => 1]), [], $user);
        $response = $this->controller->create($request);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testCreateReturns201OnSuccess(): void
    {
        $user    = $this->makeUser(1);
        $routine = $this->makeRoutine(1, 'Leg Day');
        $session = $this->makeTrainingSession(5, $user, $routine);

        $this->routineService->method('findOne')->willReturn($routine);
        $this->sessionService->method('create')->willReturn($session);

        $request  = $this->makeRequest(json_encode(['routineId' => 1, 'durationMinutes' => 40]), [], $user);
        $response = $this->controller->create($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame(5, $data['id']);
    }

    // --- update ---

    public function testUpdateReturns404WhenNotFound(): void
    {
        $user = $this->makeUser(1);
        $this->sessionService->method('findOne')->willReturn(null);

        $request  = $this->makeRequest(json_encode([]), [], $user);
        $response = $this->controller->update(99, $request);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testUpdateReturns200OnSuccess(): void
    {
        $user    = $this->makeUser(1);
        $routine = $this->makeRoutine(1);
        $session = $this->makeTrainingSession(10, $user, $routine);

        $this->sessionService->method('findOne')->willReturn($session);
        $this->sessionService->method('update')->willReturn($session);

        $request  = $this->makeRequest(json_encode(['durationMinutes' => 60]), [], $user);
        $response = $this->controller->update(10, $request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(10, $data['id']);
    }
}
