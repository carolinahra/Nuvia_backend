<?php

namespace App\Tests\Controller;

use App\Controller\NutritionController;
use App\Service\DietMealService;
use PHPUnit\Framework\MockObject\Stub;

class NutritionControllerTest extends AbstractControllerTestCase
{
    private NutritionController $controller;
    private DietMealService&Stub $dietMealService;

    protected function setUp(): void
    {
        $this->dietMealService = $this->createStub(DietMealService::class);
        $this->controller      = new NutritionController($this->dietMealService);
        $this->setUpContainer($this->controller);
    }

    public function testDishesByDietReturns200WithGroupedData(): void
    {
        $grouped = ['breakfast' => [['name' => 'Oats', 'calories' => 350]]];
        $this->dietMealService->method('getDishesByDietGroupedByMeal')->willReturn($grouped);

        $response = $this->controller->dishesByDiet(1);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertArrayHasKey('breakfast', $data);
    }

    public function testDishesByDietReturns200WithEmptyResult(): void
    {
        $this->dietMealService->method('getDishesByDietGroupedByMeal')->willReturn([]);

        $response = $this->controller->dishesByDiet(99);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([], $data);
    }
}
