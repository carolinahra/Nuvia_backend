<?php
// Nuvia_backend-main/src/Controller/ContactoController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController extends AbstractController
{
    #[Route('/contacto', name: 'procesar_contacto', methods: ['POST'])]
    public function procesar(Request $request): Response
    {
        $nombre = $request->request->get('nombre');
        $email = $request->request->get('email');
        $mensaje = $request->request->get('mensaje');

        $errores = [];

        // Validación en el servidor (PHP)
        if (empty($nombre)) {
            $errores[] = "El nombre es obligatorio.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo no es válido.";
        }
        if (empty($mensaje)) {
            $errores[] = "El mensaje es obligatorio.";
        }

        if (count($errores) > 0) {
            // Si hay errores, devolver un mensaje (en un proyecto real se devuelve a la vista)
            return new Response(implode("<br>", $errores), 400);
        }

        // Si todo es correcto, procesar (ej. enviar email o guardar en BD)
        return new Response("Formulario procesado correctamente. ¡Gracias, $nombre!", 200);
    }
}