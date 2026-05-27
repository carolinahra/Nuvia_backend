<?php

namespace App\Tests\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactoControllerTest extends WebTestCase
{
    public function testReturnsBadRequestWhenNombreIsMissing(): void
    {
        $client = static::createClient();
        $this->mockEmailService();

        $client->request('POST', '/contacto', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['nombre' => '', 'email' => 'user@example.com', 'mensaje' => 'Hola'])
        );

        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $data);
    }

    public function testReturnsBadRequestWhenEmailIsInvalid(): void
    {
        $client = static::createClient();
        $this->mockEmailService();

        $client->request('POST', '/contacto', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['nombre' => 'Ana', 'email' => 'not-an-email', 'mensaje' => 'Hola'])
        );

        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $data);
    }

    public function testReturnsBadRequestWhenMensajeIsMissing(): void
    {
        $client = static::createClient();
        $this->mockEmailService();

        $client->request('POST', '/contacto', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['nombre' => 'Ana', 'email' => 'user@example.com', 'mensaje' => ''])
        );

        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $data);
    }

    public function testReturnsOkOnValidSubmission(): void
    {
        $client = static::createClient();
        $this->mockEmailService();

        $client->request('POST', '/contacto', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Ana', 'email' => 'user@example.com', 'subject' => 'Ayuda', 'message' => 'Hola, necesito ayuda.'])
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $data);
    }

    private function mockEmailService(): void
    {
        $stub = $this->createStub(EmailService::class);
        static::getContainer()->set(EmailService::class, $stub);
    }
}
