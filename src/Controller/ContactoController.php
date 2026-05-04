<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ContactoController extends AbstractController
{
    public function __construct(
        private readonly EmailService $emailService,
    ) {}

    #[Route('/contacto', name: 'procesar_contacto', methods: ['POST'])]
    public function procesar(Request $request): JsonResponse
    {
        $data    = json_decode($request->getContent(), true);
        $name  = $data['name'] ?? '';
        $email   = $data['email'] ?? '';
        $subject = $data['subject'] ?? '';
        $message = $data['message'] ?? '';

        $errores = [];

        if (empty($name)) {
            $errores[] = 'El nombre es obligatorio.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo no es válido.';
        }
        if (empty($message)) {
            $errores[] = 'El mensaje es obligatorio.';
        }
        if (empty($subject)) {
            $errores[] = 'El asunto es obligatorio.';
        }

        if (!empty($errores)) {
            return $this->json(['errors' => $errores], 400);
        }

        try {
            $this->emailService->send(
                from: $email,
                to: 'customer.support.nuvia@gmail.com',
                subject: "[Contacto] $subject",
                message: "De: $name <$email>\n\n$message",
            );
        } catch (\Throwable) {
            return $this->json(['error' => 'No se pudo enviar el mensaje.'], 500);
        }

        return $this->json(['message' => 'Mensaje enviado correctamente.']);
    }
}
