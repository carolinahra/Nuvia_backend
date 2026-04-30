<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserWeightLogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserWeightLogController extends AbstractController
{
    public function __construct(
        private readonly UserWeightLogService $weightLogService,
    ) {}

    #[Route('/weight-logs', name: 'weight_logs_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->attributes->get('user');

        $logs = $this->weightLogService->findByUser($user->getId());

        $data = array_map(fn ($log) => [
            'id'         => $log->getId(),
            'weight_kg'  => $log->getWeightKg(),
            'created_at' => $log->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ], $logs);

        return $this->json($data);
    }

    #[Route('/weight-logs', name: 'weight_logs_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->attributes->get('user');
        $data = json_decode($request->getContent(), true);

        try {
            $log = $this->weightLogService->create([
                'user_weight_log_user'      => $user,
                'user_weight_log_weight_kg' => $data['weight_kg'] ?? null,
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'id'         => $log->getId(),
            'weight_kg'  => $log->getWeightKg(),
            'created_at' => $log->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ], 201);
    }
}
