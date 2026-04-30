<?php

namespace App\Service;

use App\Entity\TrainingSession;
use App\Repository\TrainingSessionRepository;

class TrainingSessionService
{
    public function __construct(
        private readonly TrainingSessionRepository $repository,
    ) {}

    public function findOne(int $id): ?TrainingSession
    {
        return $this->repository->find($id);
    }

    public function findMany(): array
    {
        return $this->repository->findAll();
    }

    public function findByUser(int $userId): array
    {
        return $this->repository->findByUser($userId);
    }

    public function create(array $data): TrainingSession
    {
        $trainingSession = new TrainingSession();

        if (empty($data['user'])) {
            throw new \InvalidArgumentException('User is required');
        }

        if (empty($data['routine'])) {
            throw new \InvalidArgumentException('Routine is required');
        }

        if (isset($data['durationMinutes'])) {
            $trainingSession->setDurationMinutes($data['durationMinutes']);
        }

        if (isset($data['caloriesEstimated'])) {
            $trainingSession->setCaloriesEstimated($data['caloriesEstimated']);
        }

        $trainingSession->setUser($data['user']);
        $trainingSession->setRoutine($data['routine']);

        $trainingSession->setCreatedAt(
            $data['createdAt'] ?? new \DateTime()
        );

        $this->repository->save($trainingSession, true);

        return $trainingSession;
    }

    public function update(TrainingSession $trainingSession, array $data): TrainingSession
    {
        if (isset($data['user'])) {
            $trainingSession->setUser($data['user']);
        }

        if (isset($data['routine'])) {
            $trainingSession->setRoutine($data['routine']);
        }

        if (isset($data['durationMinutes'])) {
            $trainingSession->setDurationMinutes($data['durationMinutes']);
        }

        if (isset($data['caloriesEstimated'])) {
            $trainingSession->setCaloriesEstimated($data['caloriesEstimated']);
        }

        $this->repository->save($trainingSession, true);

        return $trainingSession;
    }

    public function deleteById(int $id): void
    {
        $trainingSession = $this->findOne($id);

        if (!$trainingSession) {
            throw new \InvalidArgumentException('Training session not found');
        }

        $this->repository->remove($trainingSession, true);
    }
}