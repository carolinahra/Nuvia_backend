<?php

namespace App\Service;

use App\Exception\Auth\InvalidCredentialsException;

class LoginService
{
    public function __construct(
        private readonly UserService    $userService,
        private readonly SessionService $sessionService,
    ) {}

    public function login(string $email, string $password): array
    {
        $user = $this->userService->findOne(email: $email);

        if (!password_verify($password, $user->getPassword())) {
            throw new InvalidCredentialsException();
        }

        $session = $this->sessionService->createSession($user);

        return [
            'token' => $session->getToken(),
            'user'  => [
                'id'               => $user->getId(),
                'name'             => $user->getName(),
                'username'         => $user->getUsername(),
                'email'            => $user->getEmail(),
                'heightCm'         => $user->getHeightCm(),
                'birthdate'        => $user->getBirthdate()?->format('Y-m-d'),
                'sex'              => $user->getSex(),
                'activityLevel'    => $user->getActivityLevel(),
                'goal'             => $user->getGoal(),
                'isAdmin'          => $user->isAdmin(),
                'defaultDietId'    => $user->getDefaultDiet()?->getId(),
                'defaultRoutineId' => $user->getDefaultRoutine()?->getId(),
            ],
        ];
    }
}
