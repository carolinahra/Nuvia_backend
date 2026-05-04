<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\DietMealService;
use App\Service\DishService;
use App\Service\UserMealLogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class NutritionController extends AbstractController
{
    public function __construct(
        private readonly DietMealService    $dietMealService,
        private readonly DishService        $dishService,
        private readonly UserMealLogService $userMealLogService,
    ) {}

    #[Route('/nutrition/diet/{dietId}/dishes', name: 'nutrition_dishes_by_diet', methods: ['GET'])]
    public function dishesByDiet(int $dietId): JsonResponse
    {
        $grouped = $this->dietMealService->getDishesByDietGroupedByMeal($dietId);

        return $this->json($grouped);
    }

    #[Route('/nutrition/meal-log', name: 'nutrition_meal_log_create', methods: ['POST'])]
    public function logMeal(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->attributes->get('user');
        $data = json_decode($request->getContent(), true);

        $dish = isset($data['dishId'])
            ? $this->dishService->findOne((int) $data['dishId'])
            : null;

        if (!$dish) {
            return $this->json(['error' => 'Dish not found'], 404);
        }

        try {
            $log = $this->userMealLogService->create([
                'user_meal_log_user'     => $user,
                'user_meal_log_dish'     => $dish,
                'user_meal_log_quantity' => $data['quantity'] ?? 1,
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'id'        => $log->getId(),
            'dishId'    => $log->getDish()->getId(),
            'dishName'  => $log->getDish()->getName(),
            'quantity'  => $log->getQuantity(),
            'createdAt' => $log->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ], 201);
    }
}
