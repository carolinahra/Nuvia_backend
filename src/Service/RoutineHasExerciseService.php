<?php

namespace App\Service;

use App\Entity\RoutineHasExercise;
use App\Repository\ExerciseRepository;
use App\Repository\RoutineHasExerciseRepository;
use App\Repository\RoutineRepository;

class RoutineHasExerciseService
{
    public function __construct(
        private readonly RoutineHasExerciseRepository $repository,
        private readonly RoutineRepository $routineRepository,
        private readonly ExerciseRepository $exerciseRepository,
    ) {}

    public function findByRoutine(int $routineId): array
    {
        return $this->repository->findBy(['routine' => $routineId], ['orderIndex' => 'ASC']);
    }

    public function findOne(int $routineId, int $exerciseId): ?RoutineHasExercise
    {
        return $this->repository->findOneBy([
            'routine'  => $routineId,
            'exercise' => $exerciseId,
        ]);
    }

    public function update(RoutineHasExercise $rhe, array $data): RoutineHasExercise
    {
        if (isset($data['sets'])) {
            $rhe->setSets((int) $data['sets']);
        }
        if (isset($data['reps'])) {
            $rhe->setReps((int) $data['reps']);
        }
        if (isset($data['rest_seconds'])) {
            $rhe->setRestSeconds((int) $data['rest_seconds']);
        }
        if (isset($data['order_index'])) {
            $rhe->setOrderIndex((int) $data['order_index']);
        }

        $this->repository->save($rhe, true);
        return $rhe;
    }
}
