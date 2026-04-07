<?php

namespace App\Service;

use App\Entity\UserWeightLog;
use App\Repository\UserWeightLogRepository;

class UserWeightLogService
{
    public function __construct(
        private readonly UserWeightLogRepository $repository,
    ) {}

    public function findOne(int $id): ?UserWeightLog
    {
        return $this->repository->find($id);
    }

    public function findMany(): array
    {
        return $this->repository->findAll();
    }

    public function create(array $data): UserWeightLog
    {
        $userWeightLog = new UserWeightLog();

        if (empty($data['user_weight_log_user'])) {
            throw new \InvalidArgumentException('User is required');
        }

        if (empty($data['user_weight_log_weight_kg'])) {
            throw new \InvalidArgumentException('Weight is required');
        }

        if ($data['user_weight_log_weight_kg'] <= 0) {
            throw new \InvalidArgumentException('Weight must be greater than 0');
        }

        $userWeightLog->setUser($data['user_weight_log_user']);
        $userWeightLog->setWeightKg($data['user_weight_log_weight_kg']);

        $userWeightLog->setCreatedAt(
            $data['user_weight_log_created_at'] ?? new \DateTimeImmutable()
        );

        $this->repository->save($userWeightLog, true);

        return $userWeightLog;
    }

    public function update(UserWeightLog $userWeightLog, array $data): UserWeightLog
    {
        if (isset($data['user_weight_log_user'])) {
            $userWeightLog->setUser($data['user_weight_log_user']);
        }

        if (isset($data['user_weight_log_weight_kg'])) {
            if ($data['user_weight_log_weight_kg'] <= 0) {
                throw new \InvalidArgumentException('Weight must be greater than 0');
            }
            $userWeightLog->setWeightKg($data['user_weight_log_weight_kg']);
        }

        if (isset($data['user_weight_log_created_at'])) {
            $userWeightLog->setCreatedAt($data['user_weight_log_created_at']);
        }

        $this->repository->save($userWeightLog, true);

        return $userWeightLog;
    }

    public function deleteById(int $id): void
    {
        $userWeightLog = $this->findOne($id);

        if (!$userWeightLog) {
            throw new \InvalidArgumentException('User weight log not found');
        }

        $this->repository->remove($userWeightLog, true);
    }
}