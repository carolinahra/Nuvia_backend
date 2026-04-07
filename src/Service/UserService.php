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

        $user->setName($data['name'] ?? null);
        $user->setUserName($data['user_name'] ?? null);

        if (empty($data['email'])) {
            throw new \InvalidArgumentException('Email required');
        }

        if (empty($data['password'])) {
            throw new \InvalidArgumentException('Password required');
        }

        if (!empty($data['birth_date'])) {
            try {
                $user->setBirthdate(new \DateTime($data['birth_date']));
            } catch (\Exception $e) {
                throw new \InvalidArgumentException('Invalid birth date');
            };
        }
        $user->setEmail($data['email']);

        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setHeightCm(isset($data['height_cm']) ? (int)$data['height_cm'] : null);
        $user->setSex($data['sex'] ?? null);
        $user->setActivityLevel($data['activity_level'] ?? null);
        $user->setGoal($data['goal'] ?? null);
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
            } catch (\Exception $e) {
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

    public function deleteById(int $id):void
    {
        $user = $this->findOne(id : $id);

        $this->repository->remove($user);
    }
}
