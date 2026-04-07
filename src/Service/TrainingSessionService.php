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

    public function create(array $data): TrainingSession
    {
        $trainingSession = new TrainingSession();

        if (empty($data['training_session_user'])) {
            throw new \InvalidArgumentException('User is required');
        }

        if (empty($data['training_session_routine'])) {
            throw new \InvalidArgumentException('Routine is required');
        }

        if (isset($data['training_session_duration_minutes'])) {
            $trainingSession->setDurationMinutes($data['training_session_duration_minutes']);
        }

        if (isset($data['training_session_calories_estimated'])) {
            $trainingSession->setCaloriesEstimated($data['training_session_calories_estimated']);
        }

        $trainingSession->setUser($data['training_session_user']);
        $trainingSession->setRoutine($data['training_session_routine']);

        $trainingSession->setCreatedAt(
            $data['training_session_created_at'] ?? new \DateTimeImmutable()
        );

        $this->repository->save($trainingSession, true);

        return $trainingSession;
    }

    public function update(TrainingSession $trainingSession, array $data): TrainingSession
    {
        if (isset($data['training_session_user'])) {
            $trainingSession->setUser($data['training_session_user']);
        }

        if (isset($data['training_session_routine'])) {
            $trainingSession->setRoutine($data['training_session_routine']);
        }

        if (isset($data['training_session_duration_minutes'])) {
            $trainingSession->setDurationMinutes($data['training_session_duration_minutes']);
        }

        if (isset($data['training_session_calories_estimated'])) {
            $trainingSession->setCaloriesEstimated($data['training_session_calories_estimated']);
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