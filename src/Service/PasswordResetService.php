<?php

namespace App\Service;

use App\Exception\Auth\InvalidResetTokenException;
use App\Repository\UserRepository;

class PasswordResetService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EmailService   $emailService,
    ) {}

    public function requestReset(string $email): void
    {
        $user = $this->userRepository->get(email: $email);
        if (!$user) {
            return;
        }

        $rawToken    = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $rawToken);
        $expiresAt   = new \DateTime('+15 minutes');

        $user->setResetToken($hashedToken);
        $user->setResetTokenExpiresAt($expiresAt);
        $this->userRepository->save($user);

        $this->emailService->send(
            from: 'customer.support.nuvia@gmail.com',
            to: $user->getEmail(),
            subject: 'Recuperación de contraseña — Nuvia',
            message: "Hola {$user->getName()},\n\nHaz clic en el siguiente enlace para restablecer tu contraseña (válido 15 minutos):\n\nhttps://nuvia-frontend-8ys1.vercel.app/reset-password?token={$rawToken}\n\nSi no solicitaste esto, ignora este correo.",
        );
    }

    public function resetPassword(string $rawToken, string $newPassword): void
    {
        if (strlen($newPassword) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters');
        }

        $hashedToken = hash('sha256', $rawToken);
        $user        = $this->userRepository->findByResetToken($hashedToken);

        if (!$user || $user->getResetTokenExpiresAt() < new \DateTime()) {
            throw new InvalidResetTokenException();
        }

        $user->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
        $user->setResetToken(null);
        $user->setResetTokenExpiresAt(null);
        $this->userRepository->save($user);
    }
}
