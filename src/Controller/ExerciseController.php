<?php

namespace App\Controller;

use App\Service\ExerciseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ExerciseController extends AbstractController
{
    public function __construct(
        private readonly ExerciseService $exerciseService,
    ) {}

    #[Route('/exercises', name: 'exercises_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $exercises = $this->exerciseService->findMany();

        $data = array_map(fn ($e) => [
            'id'          => $e->getId(),
            'name'        => $e->getName(),
            'description' => $e->getDescription(),
            'intensity'   => $e->getIntensity(),
        ], $exercises);

        return $this->json($data);
    }

    #[Route('/exercises/{id}', name: 'exercises_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $exercise = $this->exerciseService->findOne($id);

        if (!$exercise) {
            return $this->json(['error' => 'Exercise not found'], 404);
        }

        return $this->json([
            'id'          => $exercise->getId(),
            'name'        => $exercise->getName(),
            'description' => $exercise->getDescription(),
            'intensity'   => $exercise->getIntensity(),
        ]);
    }

    #[Route('/exercises/{id}', name: 'exercises_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $exercise = $this->exerciseService->findOne($id);

        if (!$exercise) {
            return $this->json(['error' => 'Exercise not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $exercise = $this->exerciseService->update($exercise, $data);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'id'          => $exercise->getId(),
            'name'        => $exercise->getName(),
            'description' => $exercise->getDescription(),
            'intensity'   => $exercise->getIntensity(),
        ]);
    }
}
