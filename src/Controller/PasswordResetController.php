<?php

namespace App\Controller;

use App\Exception\Auth\InvalidResetTokenException;
use App\Service\PasswordResetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PasswordResetController extends AbstractController
{
    public function __construct(
        private readonly PasswordResetService $passwordResetService,
    ) {}

    #[Route('/password/forgot', name: 'password_forgot', methods: ['POST'])]
    public function forgot(Request $request): JsonResponse
    {
        $data  = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return $this->json(['error' => 'Email is required'], 400);
        }

        try {
            $this->passwordResetService->requestReset($email);
        } catch (\Throwable) {
            // Silently ignore — never reveal whether email exists
        }

        return $this->json(['message' => 'If that email is registered, a reset link was sent.']);
    }

    #[Route('/password/reset', name: 'password_reset', methods: ['POST'])]
    public function reset(Request $request): JsonResponse
    {
        $data     = json_decode($request->getContent(), true);
        $token    = $data['token']    ?? null;
        $password = $data['password'] ?? null;

        if (!$token || !$password) {
            return $this->json(['error' => 'Token and password are required'], 400);
        }

        try {
            $this->passwordResetService->resetPassword($token, $password);
        } catch (InvalidResetTokenException) {
            return $this->json(['error' => 'Invalid or expired token'], 400);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json(['message' => 'Password updated.']);
    }
}
