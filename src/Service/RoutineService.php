<?php

namespace App\Service;

use App\Entity\Routine;
use App\Repository\RoutineRepository;

class RoutineService
{
    public function __construct(
        private readonly RoutineRepository $repository,
    ) {}

    public function findOne(int $id): ?Routine
    {
        return $this->repository->find($id);
    }

    public function findMany(): array
    {
        return $this->repository->findAll();
    }

    public function create(array $data): Routine
    {
        $routine = new Routine();

        if (empty($data['routine_name'])) {
            throw new \InvalidArgumentException('Routine name required');
        }
        if (empty($data['routine_description'])) {
            throw new \InvalidArgumentException('Routine description required');
        }
        if (empty($data['routine_duration_minutes'])) {
            throw new \InvalidArgumentException('Routine duration minutes required');
        }
        $routine->setName($data['routine_name']);
        $routine->setDescription($data['routine_description']);
        $routine->setDurationMinutes($data['routine_duration_minutes']);
        $this->repository->save($routine, true);
        return $routine;
    }

    public function update(Routine $routine, array $data): Routine
    {
        if (isset($data['routine_name'])) {
            $routine->setName($data['routine_name']);
        }

        if (isset($data['routine_description'])) {
            $routine->setDescription($data['routine_description']);
        }

        if (isset($data['routine_duration_minutes'])) {
            $routine->setDurationMinutes($data['routine_duration_minutes']);
        }

        $this->repository->save($routine, true);
        return $routine;
    }

    public function deleteById(int $id): void
    {
        $routine = $this->findOne($id);

        if (!$routine) {
            throw new \InvalidArgumentException('Routine not found');
        }

        $this->repository->remove($routine, true);
    }
}
