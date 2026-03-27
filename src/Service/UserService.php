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
}
