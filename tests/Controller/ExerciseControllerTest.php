<?php

namespace App\Tests\Controller;

use App\Controller\ExerciseController;
use App\Service\ExerciseService;
use PHPUnit\Framework\MockObject\Stub;
use Symfony\Component\HttpFoundation\Request;

class ExerciseControllerTest extends AbstractControllerTestCase
{
    private ExerciseController $controller;
    private ExerciseService&Stub $exerciseService;

    protected function setUp(): void
    {
        $this->exerciseService = $this->createStub(ExerciseService::class);
        $this->controller      = new ExerciseController($this->exerciseService);
        $this->setUpContainer($this->controller);
    }

    // --- list ---

    public function testListReturns200WithExercises(): void
    {
        $exercise = $this->makeExercise(1, 'Push-up');
        $exercise->setDescription('Upper body')->setIntensity('alta');

        $this->exerciseService->method('findMany')->willReturn([$exercise]);

        $response = $this->controller->list();
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(1, $data);
        $this->assertSame(1, $data[0]['id']);
        $this->assertSame('Push-up', $data[0]['name']);
        $this->assertSame('Upper body', $data[0]['description']);
        $this->assertSame('alta', $data[0]['intensity']);
    }

    public function testListReturns200WithEmptyArray(): void
    {
        $this->exerciseService->method('findMany')->willReturn([]);

        $response = $this->controller->list();
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([], $data);
    }

    // --- get ---

    public function testGetReturns200WhenExerciseFound(): void
    {
        $exercise = $this->makeExercise(5, 'Squat');
        $exercise->setDescription('Leg exercise')->setIntensity('media');

        $this->exerciseService->method('findOne')->willReturn($exercise);

        $response = $this->controller->get(5);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(5, $data['id']);
        $this->assertSame('Squat', $data['name']);
        $this->assertSame('Leg exercise', $data['description']);
        $this->assertSame('media', $data['intensity']);
    }

    public function testGetReturns404WhenExerciseNotFound(): void
    {
        $this->exerciseService->method('findOne')->willReturn(null);

        $response = $this->controller->get(99);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    // --- update ---

    public function testUpdateReturns404WhenExerciseNotFound(): void
    {
        $this->exerciseService->method('findOne')->willReturn(null);

        $request  = new Request(content: json_encode(['exercise_name' => 'New name']));
        $response = $this->controller->update(99, $request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    public function testUpdateReturns400WhenServiceThrowsInvalidArgument(): void
    {
        $exercise = $this->makeExercise(1, 'Push-up');

        $this->exerciseService->method('findOne')->willReturn($exercise);
        $this->exerciseService->method('update')->willThrowException(new \InvalidArgumentException('Invalid data'));

        $request  = new Request(content: json_encode(['exercise_name' => '']));
        $response = $this->controller->update(1, $request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    public function testUpdateReturns200WithUpdatedExercise(): void
    {
        $exercise = $this->makeExercise(1, 'Push-up');
        $updated  = $this->makeExercise(1, 'Push-up modified');
        $updated->setDescription('New desc')->setIntensity('alta');

        $this->exerciseService->method('findOne')->willReturn($exercise);
        $this->exerciseService->method('update')->willReturn($updated);

        $request  = new Request(content: json_encode(['exercise_name' => 'Push-up modified']));
        $response = $this->controller->update(1, $request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Push-up modified', $data['name']);
        $this->assertSame('New desc', $data['description']);
        $this->assertSame('alta', $data['intensity']);
    }
}
