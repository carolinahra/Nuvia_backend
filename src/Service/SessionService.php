<?php

namespace App\Service;

use App\Entity\Session;
use App\Entity\User;
use App\Exception\Session\SessionNotFoundException;
use App\Repository\SessionRepository;

class SessionService
{
    public function __construct(
        private readonly SessionRepository $repository,
    ) {}

    public function createSession(User $user): Session
    {
        $token = bin2hex(random_bytes(32));
        $now   = new \DateTimeImmutable();

        $session = new Session();
        $session->setUser($user)
                ->setToken($token)
                ->setCreatedAt($now)
                ->setUpdatedAt($now);

        $this->repository->save($session);

        return $session;
    }

    public function findOne(?int $id = null, ?string $token = null): Session
    {
        $session = $this->repository->get(id: $id, token: $token);
        if (!$session) {
            throw new SessionNotFoundException();
        }
        return $session;
    }
}
