<?php

namespace App\Service;

use App\Entity\RoutineCategory;
use App\Repository\RoutineCategoryRepository;

class RoutineCategoryService
{
    public function __construct(
        private readonly RoutineCategoryRepository $repository,
    ) {}

    public function findOne(int $id): ?RoutineCategory
    {
        return $this->repository->find($id);
    }

    public function findMany(): array
    {
        return $this->repository->findAll();
    }

    public function create(array $data): RoutineCategory
    {
        $routineCategory = new RoutineCategory();

        if (empty($data['routine_category_name'])) {
            throw new \InvalidArgumentException('Routine category name required');
        }
        if (empty($data['routine_category_description'])) {
            throw new \InvalidArgumentException('Routine category description required');
        }
    
        $routineCategory->setName($data['routine_category_name']);
        $routineCategory->setDescription($data['routine_category_description']);
        $this->repository->save($routineCategory, true);
        return $routineCategory;
    }

    public function update(RoutineCategory $routineCategory, array $data): RoutineCategory
    {
        if (isset($data['routine_category_name'])) {
            $routineCategory->setName($data['routine_category_name']);
        }

        if (isset($data['routine_category_description'])) {
            $routineCategory->setDescription($data['routine_category_description']);
        }

        $this->repository->save($routineCategory, true);
        return $routineCategory;
    }

    public function deleteById(int $id): void
    {
        $routineCategory = $this->findOne($id);

        if (!$routineCategory) {
            throw new \InvalidArgumentException('Routine category not found');
        }

        $this->repository->remove($routineCategory, true);
    }
}
