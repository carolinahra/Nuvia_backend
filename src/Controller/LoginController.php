<?php

namespace App\Controller;

use App\Exception\Auth\InvalidCredentialsException;
use App\Exception\User\UserNotFoundException;
use App\Service\LoginService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    public function __construct(
        private readonly LoginService $loginService,
    ) {}

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email    = $data['email']    ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return $this->json(['error' => 'Email and password are required'], 400);
        }

        try {
            $result = $this->loginService->login($email, $password);
            return $this->json($result, 200);
        } catch (UserNotFoundException | InvalidCredentialsException $e) {
            // Return the same generic message for both cases to avoid user enumeration
            return $this->json(['error' => 'Invalid credentials'], 401);
        }
    }
}
