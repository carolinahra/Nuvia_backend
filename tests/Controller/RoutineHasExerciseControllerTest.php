<?php

namespace App\Tests\Controller;

use App\Controller\RoutineHasExerciseController;
use App\Service\RoutineHasExerciseService;
use PHPUnit\Framework\MockObject\Stub;
use Symfony\Component\HttpFoundation\Request;

class RoutineHasExerciseControllerTest extends AbstractControllerTestCase
{
    private RoutineHasExerciseController $controller;
    private RoutineHasExerciseService&Stub $rheService;

    protected function setUp(): void
    {
        $this->rheService   = $this->createStub(RoutineHasExerciseService::class);
        $this->controller   = new RoutineHasExerciseController($this->rheService);
        $this->setUpContainer($this->controller);
    }

    private function makeRhe(): \App\Entity\RoutineHasExercise
    {
        return $this->makeRoutineHasExercise(
            $this->makeRoutine(1),
            $this->makeExercise(10, 'Squat'),
        );
    }

    // --- list ---

    public function testListReturns200WithEntries(): void
    {
        $this->rheService->method('findByRoutine')->willReturn([$this->makeRhe()]);

        $response = $this->controller->list(1);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(1, $data);
        $this->assertSame('Squat', $data[0]['name']);
    }

    public function testListReturns200WithEmptyArray(): void
    {
        $this->rheService->method('findByRoutine')->willReturn([]);

        $response = $this->controller->list(1);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([], $data);
    }

    // --- get ---

    public function testGetReturns200WhenFound(): void
    {
        $this->rheService->method('findOne')->willReturn($this->makeRhe());

        $response = $this->controller->get(1, 10);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(10, $data['exerciseId']);
    }

    public function testGetReturns404WhenNotFound(): void
    {
        $this->rheService->method('findOne')->willReturn(null);

        $response = $this->controller->get(1, 99);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    // --- update ---

    public function testUpdateReturns404WhenNotFound(): void
    {
        $this->rheService->method('findOne')->willReturn(null);

        $response = $this->controller->update(1, 99, new Request(content: json_encode([])));

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testUpdateReturns200OnSuccess(): void
    {
        $rhe     = $this->makeRhe();
        $updated = $this->makeRoutineHasExercise($this->makeRoutine(1), $this->makeExercise(10, 'Squat'));
        $updated->setSets(5)->setReps(8);

        $this->rheService->method('findOne')->willReturn($rhe);
        $this->rheService->method('update')->willReturn($updated);

        $response = $this->controller->update(1, 10, new Request(content: json_encode(['sets' => 5, 'reps' => 8])));
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(5, $data['sets']);
        $this->assertSame(8, $data['reps']);
    }
}
