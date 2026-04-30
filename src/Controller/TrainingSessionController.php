<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\RoutineService;
use App\Service\TrainingSessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class TrainingSessionController extends AbstractController
{
    public function __construct(
        private readonly TrainingSessionService $trainingSessionService,
        private readonly RoutineService $routineService,
    ) {}

    #[Route('/training-sessions', name: 'training_sessions_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->attributes->get('user');

        $sessions = $this->trainingSessionService->findByUser($user->getId());

        $data = array_map(fn ($s) => [
            'id'                => $s->getId(),
            'routineId'         => $s->getRoutine()->getId(),
            'routineName'       => $s->getRoutine()->getName(),
            'durationMinutes'   => $s->getDurationMinutes(),
            'caloriesEstimated' => $s->getCaloriesEstimated(),
            'createdAt'         => $s->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ], $sessions);

        return $this->json($data);
    }

    #[Route('/training-sessions/{id}', name: 'training_sessions_get', methods: ['GET'])]
    public function get(int $id, Request $request): JsonResponse
    {
        /** @var User $user */
        $user    = $request->attributes->get('user');
        $session = $this->trainingSessionService->findOne($id);

        if (!$session || $session->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Training session not found'], 404);
        }

        return $this->json([
            'id'                => $session->getId(),
            'routineId'         => $session->getRoutine()->getId(),
            'routineName'       => $session->getRoutine()->getName(),
            'durationMinutes'   => $session->getDurationMinutes(),
            'caloriesEstimated' => $session->getCaloriesEstimated(),
            'createdAt'         => $session->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ]);
    }

    #[Route('/training-sessions', name: 'training_sessions_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->attributes->get('user');
        $data = json_decode($request->getContent(), true);

        $routine = isset($data['routineId'])
            ? $this->routineService->findOne((int) $data['routineId'])
            : null;

        if (!$routine) {
            return $this->json(['error' => 'Routine not found'], 404);
        }

        try {
            $session = $this->trainingSessionService->create([
                'training_session_user'               => $user,
                'training_session_routine'            => $routine,
                'training_session_duration_minutes'   => $data['durationMinutes'] ?? null,
                'training_session_calories_estimated' => $data['caloriesEstimated'] ?? null,
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'id'                => $session->getId(),
            'routineId'         => $session->getRoutine()->getId(),
            'routineName'       => $session->getRoutine()->getName(),
            'durationMinutes'   => $session->getDurationMinutes(),
            'caloriesEstimated' => $session->getCaloriesEstimated(),
            'createdAt'         => $session->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ], 201);
    }

    #[Route('/training-sessions/{id}', name: 'training_sessions_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        /** @var User $user */
        $user    = $request->attributes->get('user');
        $session = $this->trainingSessionService->findOne($id);

        if (!$session || $session->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Training session not found'], 404);
        }

        $data    = json_decode($request->getContent(), true);
        $payload = [];

        if (isset($data['routineId'])) {
            $routine = $this->routineService->findOne((int) $data['routineId']);
            if (!$routine) {
                return $this->json(['error' => 'Routine not found'], 404);
            }
            $payload['training_session_routine'] = $routine;
        }

        if (isset($data['durationMinutes'])) {
            $payload['training_session_duration_minutes'] = $data['durationMinutes'];
        }

        if (isset($data['caloriesEstimated'])) {
            $payload['training_session_calories_estimated'] = $data['caloriesEstimated'];
        }

        $session = $this->trainingSessionService->update($session, $payload);

        return $this->json([
            'id'                => $session->getId(),
            'routineId'         => $session->getRoutine()->getId(),
            'routineName'       => $session->getRoutine()->getName(),
            'durationMinutes'   => $session->getDurationMinutes(),
            'caloriesEstimated' => $session->getCaloriesEstimated(),
            'createdAt'         => $session->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ]);
    }
}
