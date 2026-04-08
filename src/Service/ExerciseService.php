<?php

namespace App\Service;

use App\Entity\Exercise;
use App\Repository\ExerciseRepository;

class ExerciseService
{
    public function __construct(
        private readonly ExerciseRepository $repository,
    ) {}

    public function findOne(int $id): ?Exercise
    {
        return $this->repository->find($id);
    }

    public function findMany(): array
    {
        return $this->repository->findAll();
    }

    public function create(array $data): Exercise
    {
        $exercise = new Exercise();

if (empty($data['exercise_name'])) {
            throw new \InvalidArgumentException('Exercise name required');
        }
        if (empty($data['exercise_description'])) {
            throw new \InvalidArgumentException('Exercise description required');
        }
        if (empty($data['exercise_intensity'])) {
            throw new \InvalidArgumentException('Exercise intensity required');
        }
        $exercise->setName($data['exercise_name']);
        $exercise->setDescription($data['exercise_description']);
        $exercise->setIntensity($data['exercise_intensity']);
        $this->repository->save($exercise, true);
        return $exercise;
    }

    public function update(Exercise $exercise, array $data): Exercise
    {
        if (isset($data['exercise_name'])) {
            $exercise->setName($data['exercise_name']);
        }

        if(isset($data['exercise_description'])){
            $exercise->setDescription($data['exercise_description']);
        }

        if(isset($data['exercise_intensity'])){
            $exercise->setIntensity($data['exercise_intensity']);
        }

        $this->repository->save($exercise, true);
        return $exercise;
    }

    public function deleteById(int $id): void
    {
        $exercise = $this->findOne($id);

        if (!$exercise) {
            throw new \InvalidArgumentException('Exercise not found');
        }

        $this->repository->remove($exercise, true);
    }
}
