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
        return [KernelEvents::REQUEST => ['onKernelRequest', 10]];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) return;

        $request = $event->getRequest();

        if (\in_array($request->attributes->get('_route'), self::PUBLIC_ROUTES, true)) return;

        $authHeader = $request->headers->get('Authorization', '');
        $token = str_starts_with($authHeader, 'Bearer ') ? substr($authHeader, 7) : null;

        try {
            $session = $token ? $this->sessionService->findOne(token: $token) : throw new SessionNotFoundException();
            $request->attributes->set('_user', $session->getUser());
        } catch (SessionNotFoundException) {
            $event->setResponse(new JsonResponse(['error' => 'Unauthorized'], 401));
        }
    }
}
