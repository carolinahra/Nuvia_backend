<?php

namespace App\Service;

use App\Entity\DietMeal;
use App\Repository\DietMealRepository;

class DietMealService
{
    public function __construct(
        private readonly DietMealRepository $repository,
    ) {}

    public function findOne(int $id): ?DietMeal
    {
        return $this->repository->find($id);
    }

    public function findMany(): array
    {
        return $this->repository->findAll();
    }

    public function create(array $data): DietMeal
    {
        $dietMeal = new DietMeal();

        if (empty($data['diet_meal_meal_type'])) {
            throw new \InvalidArgumentException('Meal Type required');
        }
        if (empty($data['diet_meal_day_of_week'])) {
            throw new \InvalidArgumentException('Day of Week required');
        }

        $dietMeal->setMealType($data['diet_meal_meal_type']);
        $dietMeal->setDayOfWeek($data['diet_meal_day_of_week']);
        $this->repository->save($dietMeal, true);
        return $dietMeal;
    }

    public function update(DietMeal $dietMeal, array $data): DietMeal
    {
        if (isset($data['diet_meal_meal_type'])) {
            $dietMeal->setMealType($data['diet_meal_meal_type']);
        }
        if (isset($data['diet_meal_day_of_week'])) {
            $dietMeal->setDayOfWeek($data['diet_meal_day_of_week']);
        }

        $this->repository->save($dietMeal, true);
        return $dietMeal;
    }

    public function deleteById(int $id): void
    {
        $dietMeal = $this->findOne($id);

        if (!$dietMeal) {
            throw new \InvalidArgumentException('Diet Meal not found');
        }

        $this->repository->remove($dietMeal, true);
    }
}
