<?php

namespace App\Tests\Controller;

use App\Controller\UserMealLogController;
use App\Service\DishService;
use App\Service\UserMealLogService;
use PHPUnit\Framework\MockObject\Stub;

class UserMealLogControllerTest extends AbstractControllerTestCase
{
    private UserMealLogController $controller;
    private UserMealLogService&Stub $mealLogService;
    private DishService&Stub $dishService;

    protected function setUp(): void
    {
        $this->mealLogService = $this->createStub(UserMealLogService::class);
        $this->dishService    = $this->createStub(DishService::class);
        $this->controller     = new UserMealLogController($this->mealLogService, $this->dishService);
        $this->setUpContainer($this->controller);
    }

    // --- index ---

    public function testIndexReturns200WithLogs(): void
    {
        $user = $this->makeUser(1);
        $dish = $this->makeDish(5, 'Salad');
        $log  = $this->makeMealLog(1, $user, $dish);

        $this->mealLogService->method('findByUser')->willReturn([$log]);

        $request  = $this->makeRequest(null, ['date' => '2026-01-01'], $user);
        $response = $this->controller->index($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(1, $data);
        $this->assertSame('Salad', $data[0]['dishName']);
    }

    public function testIndexReturns400WhenDateInvalid(): void
    {
        if (extension_loaded('xdebug')) {
            $this->markTestSkipped('DateMalformedStringException final class incompatible with xdebug dynamic properties in PHP 8.3');
        }

        $user    = $this->makeUser(1);
        $request = $this->makeRequest(null, ['date' => 'not-a-date'], $user);

        $response = $this->controller->index($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    // --- create ---

    public function testCreateReturns404WhenDishNotFound(): void
    {
        $user = $this->makeUser(1);
        $this->dishService->method('findOne')->willReturn(null);

        $request  = $this->makeRequest(json_encode(['dishId' => 99, 'quantity' => 1]), [], $user);
        $response = $this->controller->create($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    public function testCreateReturns201OnSuccess(): void
    {
        $user = $this->makeUser(1);
        $dish = $this->makeDish(5, 'Pasta');
        $log  = $this->makeMealLog(10, $user, $dish);

        $this->dishService->method('findOne')->willReturn($dish);
        $this->mealLogService->method('create')->willReturn($log);

        $request  = $this->makeRequest(json_encode(['dishId' => 5, 'quantity' => 2]), [], $user);
        $response = $this->controller->create($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame(10, $data['id']);
        $this->assertSame('Pasta', $data['dishName']);
    }
}
