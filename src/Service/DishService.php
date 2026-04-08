<?php

namespace App\Service;

use App\Entity\Dish;
use App\Repository\DishRepository;

class DishService
{
    public function __construct(
        private readonly DishRepository $repository,
    ) {}

    public function findOne(int $id): ?Dish
    {
        return $this->repository->find($id);
    }

    public function findMany(): array
    {
        return $this->repository->findAll();
    }

    public function create(array $data): Dish
    {
        $dish = new Dish();

        if (empty($data['dish_name'])) {
            throw new \InvalidArgumentException('Dish name required');
        }
        if (empty($data['dish_ingredients'])) {
            throw new \InvalidArgumentException('Dish ingredients required');
        }
        if (empty($data['dish_calories_per_serving'])) {
            throw new \InvalidArgumentException('Dish calories per serving required');
        }

        $dish->setName($data['dish_name']);
        $dish->setIngredients($data['dish_ingredients']);
        $dish->setCaloriesPerServing($data['dish_calories_per_serving']);
        $this->repository->save($dish, true);
        return $dish;
    }

    public function update(Dish $dish, array $data): Dish
    {
        if (isset($data['dish_name'])) {
            $dish->setName($data['dish_name']);
        }
        if (isset($data['dish_ingredients'])) {
            $dish->setIngredients($data['dish_ingredients']);
        }
        if (isset($data['dish_calories_per_serving'])) {
            $dish->setCaloriesPerServing($data['dish_calories_per_serving']);
        }

        $this->repository->save($dish, true);
        return $dish;
    }

    public function deleteById(int $id): void
    {
        $dish = $this->findOne($id);

        if (!$dish) {
            throw new \InvalidArgumentException('Dish not found');
        }

        $this->repository->remove($dish, true);
    }
}
