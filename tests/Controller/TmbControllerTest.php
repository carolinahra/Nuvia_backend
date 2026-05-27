<?php

namespace App\Tests\Controller;

use App\Controller\TmbController;
use App\Service\TmbService;
use PHPUnit\Framework\MockObject\Stub;

class TmbControllerTest extends AbstractControllerTestCase
{
    private TmbController $controller;
    private TmbService&Stub $tmbService;

    protected function setUp(): void
    {
        $this->tmbService = $this->createStub(TmbService::class);
        $this->controller = new TmbController($this->tmbService);
        $this->setUpContainer($this->controller);
    }

    public function testCalculateReturns200WithResult(): void
    {
        $user   = $this->makeUser(1);
        $result = ['tmb' => 1500.0, 'tdee' => 2000.0];
        $this->tmbService->method('calculate')->willReturn($result);

        $request  = $this->makeRequest(null, [], $user);
        $response = $this->controller->calculate($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertArrayHasKey('tmb', $data);
    }

    public function testCalculateReturns400WhenInvalidArgument(): void
    {
        $user = $this->makeUser(1);
        $this->tmbService->method('calculate')->willThrowException(new \InvalidArgumentException('Missing data'));

        $request  = $this->makeRequest(null, [], $user);
        $response = $this->controller->calculate($request);
        $data     = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }
}
