<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\DishService;
use App\Service\UserMealLogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserMealLogController extends AbstractController
{
    public function __construct(
        private readonly UserMealLogService $userMealLogService,
        private readonly DishService        $dishService,
    ) {}

    #[Route('/user-meal-log', name: 'nutrition_meal_log_list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->attributes->get('user');

        try {
            $date = $request->query->get('date');
            $from = $request->query->get('from');
            $to   = $request->query->get('to');

            if ($date !== null) {
                $from = new \DateTimeImmutable($date . ' 00:00:00');
                $to   = new \DateTimeImmutable($date . ' 23:59:59');
            } else {
                $from = $from !== null ? new \DateTimeImmutable($from . ' 00:00:00') : null;
                $to   = $to   !== null ? new \DateTimeImmutable($to   . ' 23:59:59') : null;
            }
        } catch (\Exception) {
            return $this->json(['error' => 'Invalid date format, use YYYY-MM-DD'], 400);
        }

        $logs = $this->userMealLogService->findByUser($user->getId(), $from, $to);

        return $this->json(array_map(fn($log) => [
            'id'        => $log->getId(),
            'dishId'    => $log->getDish()->getId(),
            'dishName'  => $log->getDish()->getName(),
            'calories'  => $log->getDish()->getCaloriesPerServing(),
            'quantity'  => $log->getQuantity(),
            'createdAt' => $log->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ], $logs));
    }

    #[Route('/user-meal-log', name: 'nutrition_meal_log_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
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
