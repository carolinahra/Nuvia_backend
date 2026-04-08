<?php

namespace App\EventSubscriber;

use App\Exception\Session\SessionNotFoundException;
use App\Service\SessionService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthSubscriber implements EventSubscriberInterface
{
    private const PUBLIC_ROUTES = ['login'];

    public function __construct(
        private readonly SessionService $sessionService,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request   = $event->getRequest();
        $routeName = $request->attributes->get('_route');

        if (in_array($routeName, self::PUBLIC_ROUTES, true)) {
            return;
        }

        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            $event->setResponse(new JsonResponse(['error' => 'Missing token'], 401));
            return;
        }

        $token = substr($authHeader, 7);

        try {
            $session = $this->sessionService->findOne(token: $token);
            $request->attributes->set('_user', $session->getUser());
        } catch (SessionNotFoundException) {
            $event->setResponse(new JsonResponse(['error' => 'Invalid or expired token'], 401));
        }
    }
}
