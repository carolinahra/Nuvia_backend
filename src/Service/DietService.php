<?php

namespace App\Service;

use App\Entity\Diet;
use App\Repository\DietRepository;

class DietService
{
    public function __construct(
        private readonly DietRepository $repository,
    ) {}

    public function findOne(int $id): ?Diet
    {
        return $this->repository->find($id);
    }

    public function findMany(): array
    {
        return $this->repository->findAll();
    }

    public function create(array $data): Diet
    {
        $diet = new Diet();

        if (empty($data['diet_name'])) {
            throw new \InvalidArgumentException('Diet name required');
        }

        if (empty($data['diet_description'])) {
            throw new \InvalidArgumentException('Diet description required');
        }

        if (empty($data['diet_total_daily_calories'])) {
            throw new \InvalidArgumentException('Total daily calories required');
        }

        if (empty($data['diet_goal'])) {
            throw new \InvalidArgumentException('Diet goal required');
        }

        if (empty($data['diet_type'])) {
            throw new \InvalidArgumentException('Diet type required');
        }
        $diet->setName($data['diet_name']);
        $diet->setDescription($data['diet_description']);
        $diet->setTotalDailyCalories($data['diet_total_daily_calories'] ?? null);
        $diet->setGoal($data['diet_goal']);
        $diet->setDietType($data['diet_type']);
        $this->repository->save($diet, true);
        return $diet;
    }

    public function update(Diet $diet, array $data): Diet
    {
        if (isset($data['diet_name'])) {
            $diet->setName($data['diet_name']);
        }

        if (isset($data['diet_description'])) {
            $diet->setDescription($data['diet_description']);
        }

        if (isset($data['diet_total_daily_calories'])) {
            $diet->setTotalDailyCalories($data['diet_total_daily_calories']);
        }

        if (isset($data['diet_goal'])) {
            $diet->setGoal($data['diet_goal']);
        }

        if (isset($data['diet_type'])) {
            $diet->setDietType($data['diet_type']);
        }

        $this->repository->save($diet, true);
        return $diet;
    }

    public function deleteById(int $id): void
    {
        $diet = $this->findOne($id);

        if (!$diet) {
            throw new \Exception('Diet not found');
        }

        $this->repository->remove($diet, true);
    }
}
