<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
    ) {}

    public function findOne(?int $id = null, ?string $email = null): User
    {
        $user = $this->repository->get(id: $id, email: $email);
        if (!$user) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    public function findMany(?int $limit = null, ?int $offset = null): array
    {
        return $this->repository->get(limit: $limit, offset: $offset);
    }

    public function create(array $data): User
    {
        $user = new User();

        if (empty($data['name'])) {
            throw new \InvalidArgumentException('Name required');
        }

        if (empty($data['username'])) {
            throw new \InvalidArgumentException('Username required');
        }

        if (empty($data['email'])) {
            throw new \InvalidArgumentException('Email required');
        }

        if (empty($data['password'])) {
            throw new \InvalidArgumentException('Password required');
        }

        $user->setName($data['name']);
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setHeightCm(isset($data['heightCm']) ? (int)$data['heightCm'] : null);
        $user->setSex($data['sex'] ?? null);
        $user->setActivityLevel($data['activityLevel'] ?? null);

        if (!empty($data['birthdate'])) {
            try {
                $user->setBirthdate(new \DateTime($data['birthdate']));
            } catch (\Exception) {
                throw new \InvalidArgumentException('Invalid birthdate');
            }
        }

        $currentWeight = isset($data['currentWeightKg']) ? (float)$data['currentWeightKg'] : null;
        $targetWeight  = isset($data['targetWeightKg']) ? (float)$data['targetWeightKg'] : null;

        if ($currentWeight !== null && $targetWeight !== null) {
            if ($currentWeight > $targetWeight) {
                $goal = 'lose';
            } elseif ($currentWeight < $targetWeight) {
                $goal = 'gain';
            } else {
                $goal = 'maintain';
            }
        } else {
            $goal = null;
        }

        $user->setGoal($goal);
        $user->setIsAdmin(isset($data['is_admin']) ? (bool)$data['is_admin'] : false);
        $this->repository->save($user);

        return $user;
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['name'])) {
            $user->setName($data['name']);
        }

        if (isset($data['userName'])) {
            $user->setUserName($data['userName']);
        }

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (!empty($data['password'])) {
            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        }

        if (isset($data['height_cm'])) {
            $user->setHeightCm((int)$data['height_cm']);
        }

        if (!empty($data['birth_date'])) {
            try {
                $user->setBirthdate(new \DateTime($data['birth_date']));
            } catch (\Exception) {
                throw new \InvalidArgumentException('Invalid birth_date');
            }
        }

        if (isset($data['sex'])) {
            $user->setSex($data['sex']);
        }

        if (isset($data['activity_level'])) {
            $user->setActivityLevel($data['activity_level']);
        }

        if (isset($data['goal'])) {
            $user->setGoal($data['goal']);
        }

        if (isset($data['is_admin'])) {
            $user->setIsAdmin((bool)$data['is_admin']);
        }

        $this->repository->save($user);

        return $user;
    }

    public function setDefaultDiet(User $user, \App\Entity\Diet $diet): void
    {
        $user->setDefaultDiet($diet);
        $this->repository->save($user);
    }

    public function deleteById(int $id):void
    {
        $user = $this->findOne(id : $id);

        $this->repository->remove($user);
    }
}
