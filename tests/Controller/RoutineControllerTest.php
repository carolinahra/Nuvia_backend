<?php

namespace App\Tests\Controller;

use App\Controller\RoutineController;
use App\Service\RoutineService;
use PHPUnit\Framework\MockObject\Stub;
use Symfony\Component\HttpFoundation\Request;

class RoutineControllerTest extends AbstractControllerTestCase
{
    private RoutineController $controller;
    private RoutineService&Stub $routineService;

    protected function setUp(): void
    {
        $this->routineService = $this->createStub(RoutineService::class);
        $this->controller     = new RoutineController($this->routineService);
        $this->setUpContainer($this->controller);
    }

    // --- list ---

    public function testListReturns200WithRoutines(): void
    {
        $routine = $this->makeRoutine(1, 'Full Body');
        $this->routineService->method('findMany')->willReturn([$routine]);

        $response = $this->controller->list();
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(1, $data);
        $this->assertSame('Full Body', $data[0]['name']);
    }

    public function testListReturns200WithEmptyArray(): void
    {
        $this->routineService->method('findMany')->willReturn([]);

        $response = $this->controller->list();
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([], $data);
    }

    // --- get ---

    public function testGetReturns200WithRoutineAndExercises(): void
    {
        $routine  = $this->makeRoutine(1, 'Push Day');
        $exercise = $this->makeExercise(10, 'Bench Press');
        $rhe      = $this->makeRoutineHasExercise($routine, $exercise);
        $routine->addRoutineHasExercise($rhe);

        $this->routineService->method('findOne')->willReturn($routine);

        $response = $this->controller->get(1);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Push Day', $data['name']);
        $this->assertCount(1, $data['exercises']);
        $this->assertSame('Bench Press', $data['exercises'][0]['name']);
    }

    public function testGetReturns404WhenNotFound(): void
    {
        $this->routineService->method('findOne')->willReturn(null);

        $response = $this->controller->get(99);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    // --- update ---

    public function testUpdateReturns404WhenNotFound(): void
    {
        $this->routineService->method('findOne')->willReturn(null);

        $response = $this->controller->update(99, new Request(content: json_encode([])));

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testUpdateReturns400WhenInvalidArgument(): void
    {
        $routine = $this->makeRoutine(1);
        $this->routineService->method('findOne')->willReturn($routine);
        $this->routineService->method('update')->willThrowException(new \InvalidArgumentException('Bad data'));

        $response = $this->controller->update(1, new Request(content: json_encode(['name' => ''])));
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    public function testUpdateReturns200OnSuccess(): void
    {
        $routine = $this->makeRoutine(1, 'Old Name');
        $updated = $this->makeRoutine(1, 'New Name');
        $this->routineService->method('findOne')->willReturn($routine);
        $this->routineService->method('update')->willReturn($updated);

        $response = $this->controller->update(1, new Request(content: json_encode(['name' => 'New Name'])));
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('New Name', $data['name']);
    }
}
