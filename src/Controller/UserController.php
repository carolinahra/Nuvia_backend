<?php

namespace App\Controller;

use App\Service\UserService;
use App\Service\UserWeightLogService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserWeightLogService $userWeightLogService
    ) {}

    #[Route('/users', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $user = $this->userService->create($data);
            $this->userWeightLogService->create([
                'user' => $user,
                'weightKg' => $data['currentWeightKg'] ?? null
            ]);
            return $this->json(['id' => $user->getId()], 201);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (UniqueConstraintViolationException) {
            return $this->json(['error' => 'Email or username already in use'], 409);
        }
    }
}
