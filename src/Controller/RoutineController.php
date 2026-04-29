<?php

namespace App\Controller;

use App\Service\RoutineService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RoutineController extends AbstractController
{
    public function __construct(
        private readonly RoutineService $routineService,
    ) {}

    #[Route('/routines', name: 'routines_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $routines = $this->routineService->findMany();

        $data = array_map(fn ($r) => [
            'id'               => $r->getId(),
            'name'             => $r->getName(),
            'description'      => $r->getDescription(),
            'duration_minutes' => $r->getDurationMinutes(),
        ], $routines);

        return $this->json($data);
    }

    #[Route('/routines/{id}', name: 'routines_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $routine = $this->routineService->findOne($id);

        if (!$routine) {
            return $this->json(['error' => 'Routine not found'], 404);
        }

        $exercises = array_map(fn ($rhe) => [
            'exercise_id'  => $rhe->getExercise()->getId(),
            'name'         => $rhe->getExercise()->getName(),
            'description'  => $rhe->getExercise()->getDescription(),
            'intensity'    => $rhe->getExercise()->getIntensity(),
            'sets'         => $rhe->getSets(),
            'reps'         => $rhe->getReps(),
            'rest_seconds' => $rhe->getRestSeconds(),
            'order_index'  => $rhe->getOrderIndex(),
        ], $routine->getRoutineHasExercises()->toArray());

        return $this->json([
            'id'               => $routine->getId(),
            'name'             => $routine->getName(),
            'description'      => $routine->getDescription(),
            'duration_minutes' => $routine->getDurationMinutes(),
            'exercises'        => $exercises,
        ]);
    }

    #[Route('/routines/{id}', name: 'routines_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $routine = $this->routineService->findOne($id);

        if (!$routine) {
            return $this->json(['error' => 'Routine not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $routine = $this->routineService->update($routine, $data);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'id'               => $routine->getId(),
            'name'             => $routine->getName(),
            'description'      => $routine->getDescription(),
            'duration_minutes' => $routine->getDurationMinutes(),
        ]);
    }
}
