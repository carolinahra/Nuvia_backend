<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\TmbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class TmbController extends AbstractController
{
    public function __construct(
        private readonly TmbService $tmbService,
    ) {}

    #[Route('/tmb', name: 'tmb_calculate', methods: ['GET'])]
    public function calculate(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->attributes->get('user');

        $activityLevel = $request->query->get('activityLevel', $user->getActivityLevel() ?? 'sedentary');

        try {
            $result = $this->tmbService->calculate($user, $activityLevel);
            return $this->json($result);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
