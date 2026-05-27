<?php

namespace App\Tests\Controller;

use App\Controller\LoginController;
use App\Exception\Auth\InvalidCredentialsException;
use App\Service\LoginService;
use PHPUnit\Framework\MockObject\Stub;

class LoginControllerTest extends AbstractControllerTestCase
{
    private LoginController $controller;
    private LoginService&Stub $loginService;

    protected function setUp(): void
    {
        $this->loginService = $this->createStub(LoginService::class);
        $this->controller   = new LoginController($this->loginService);
        $this->setUpContainer($this->controller);
    }

    public function testLoginReturnsBadRequestWhenEmailMissing(): void
    {
        $request  = $this->makeRequest(json_encode(['password' => 'secret']));
        $response = $this->controller->login($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    public function testLoginReturnsBadRequestWhenPasswordMissing(): void
    {
        $request  = $this->makeRequest(json_encode(['email' => 'a@b.com']));
        $response = $this->controller->login($request);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testLoginReturns401WhenInvalidCredentials(): void
    {
        $this->loginService->method('login')->willThrowException(new InvalidCredentialsException());

        $request  = $this->makeRequest(json_encode(['email' => 'a@b.com', 'password' => 'wrong']));
        $response = $this->controller->login($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(401, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    public function testLoginReturns200OnSuccess(): void
    {
        $this->loginService->method('login')->willReturn(['token' => 'abc123', 'user' => ['id' => 1]]);

        $request  = $this->makeRequest(json_encode(['email' => 'a@b.com', 'password' => 'secret']));
        $response = $this->controller->login($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', $data);
    }
}
