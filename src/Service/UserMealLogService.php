<?php

namespace App\Service;

use App\Entity\UserMealLog;
use App\Repository\UserMealLogRepository;

class UserMealLogService
{
    public function __construct(
        private readonly UserMealLogRepository $repository,
    ) {}

    public function findOne(int $id): ?UserMealLog
    {
        return $this->repository->find($id);
    }

    public function findMany(): array
    {
        return $this->repository->findAll();
    }

    public function create(array $data): UserMealLog
    {
        $userMealLog = new UserMealLog();

        if (empty($data['user_meal_log_user'])) {
            throw new \InvalidArgumentException('User is required');
        }

        if (empty($data['user_meal_log_dish'])) {
            throw new \InvalidArgumentException('Dish is required');
        }

        // Quantity (opcional, default 1 en entidad)
        if (isset($data['user_meal_log_quantity'])) {
            if ($data['user_meal_log_quantity'] <= 0) {
                throw new \InvalidArgumentException('Quantity must be greater than 0');
            }
            $userMealLog->setQuantity($data['user_meal_log_quantity']);
        }

        $userMealLog->setUser($data['user_meal_log_user']);
        $userMealLog->setDish($data['user_meal_log_dish']);

        $userMealLog->setCreatedAt(
            $data['user_meal_log_created_at'] ?? new \DateTimeImmutable()
        );

        $this->repository->save($userMealLog, true);

        return $userMealLog;
    }

    public function update(UserMealLog $userMealLog, array $data): UserMealLog
    {
        if (isset($data['user_meal_log_user'])) {
            $userMealLog->setUser($data['user_meal_log_user']);
        }

        if (isset($data['user_meal_log_dish'])) {
            $userMealLog->setDish($data['user_meal_log_dish']);
        }

        if (isset($data['user_meal_log_quantity'])) {
            if ($data['user_meal_log_quantity'] <= 0) {
                throw new \InvalidArgumentException('Quantity must be greater than 0');
            }
            $userMealLog->setQuantity($data['user_meal_log_quantity']);
        }

        $this->repository->save($userMealLog, true);

        return $userMealLog;
    }

    public function deleteById(int $id): void
    {
        $userMealLog = $this->findOne($id);

        if (!$userMealLog) {
            throw new \InvalidArgumentException('User meal log not found');
        }

        $this->repository->remove($userMealLog, true);
    }
}