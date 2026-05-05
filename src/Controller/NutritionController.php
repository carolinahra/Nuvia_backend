<?php

namespace App\Controller;

use App\Service\DietMealService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class NutritionController extends AbstractController
{
    public function __construct(
        private readonly DietMealService $dietMealService,
    ) {}

    #[Route('/nutrition/diet/{dietId}/dishes', name: 'nutrition_dishes_by_diet', methods: ['GET'])]
    public function dishesByDiet(int $dietId): JsonResponse
    {
        $grouped = $this->dietMealService->getDishesByDietGroupedByMeal($dietId);

        return $this->json($grouped);
    }
}
