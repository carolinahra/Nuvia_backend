<?php

namespace App\Controller;

use App\Service\RoutineHasExerciseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RoutineHasExerciseController extends AbstractController
{
    public function __construct(
        private readonly RoutineHasExerciseService $routineHasExerciseService,
    ) {}

    #[Route('/routines/{routineId}/exercises', name: 'routine_exercises_list', methods: ['GET'])]
    public function list(int $routineId): JsonResponse
    {
        $entries = $this->routineHasExerciseService->findByRoutine($routineId);

        $data = array_map(fn ($rhe) => [
            'exerciseId'   => $rhe->getExercise()->getId(),
            'name'         => $rhe->getExercise()->getName(),
            'description'  => $rhe->getExercise()->getDescription(),
            'intensity'    => $rhe->getExercise()->getIntensity(),
            'sets'         => $rhe->getSets(),
            'reps'         => $rhe->getReps(),
            'restSeconds'  => $rhe->getRestSeconds(),
            'orderIndex'   => $rhe->getOrderIndex(),
        ], $entries);

        return $this->json($data);
    }

    #[Route('/routines/{routineId}/exercises/{exerciseId}', name: 'routine_exercise_get', methods: ['GET'])]
    public function get(int $routineId, int $exerciseId): JsonResponse
    {
        $rhe = $this->routineHasExerciseService->findOne($routineId, $exerciseId);

        if (!$rhe) {
            return $this->json(['error' => 'Entry not found'], 404);
        }

        return $this->json([
            'exerciseId'   => $rhe->getExercise()->getId(),
            'name'         => $rhe->getExercise()->getName(),
            'description'  => $rhe->getExercise()->getDescription(),
            'intensity'    => $rhe->getExercise()->getIntensity(),
            'sets'         => $rhe->getSets(),
            'reps'         => $rhe->getReps(),
            'restSeconds'  => $rhe->getRestSeconds(),
            'orderIndex'   => $rhe->getOrderIndex(),
        ]);
    }

    #[Route('/routines/{routineId}/exercises/{exerciseId}', name: 'routine_exercise_update', methods: ['PUT'])]
    public function update(int $routineId, int $exerciseId, Request $request): JsonResponse
    {
        $rhe = $this->routineHasExerciseService->findOne($routineId, $exerciseId);

        if (!$rhe) {
            return $this->json(['error' => 'Entry not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $rhe  = $this->routineHasExerciseService->update($rhe, $data);
        

        return $this->json([
            'exerciseId'   => $rhe->getExercise()->getId(),
            'sets'         => $rhe->getSets(),
            'reps'         => $rhe->getReps(),
            'restSeconds'  => $rhe->getRestSeconds(),
            'orderIndex'   => $rhe->getOrderIndex(),
        ]);
    }
}
