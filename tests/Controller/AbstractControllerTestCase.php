<?php

namespace App\Tests\Controller;

use App\Entity\Dish;
use App\Entity\Exercise;
use App\Entity\Routine;
use App\Entity\RoutineHasExercise;
use App\Entity\TrainingSession;
use App\Entity\User;
use App\Entity\UserMealLog;
use App\Entity\UserWeightLog;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractControllerTestCase extends TestCase
{
    protected function setUpContainer(AbstractController $controller): void
    {
        $container = $this->createStub(ContainerInterface::class);
        $container->method('has')->willReturn(false);
        $controller->setContainer($container);
    }

    protected function setProp(object $entity, string $prop, mixed $value): void
    {
        $ref = new \ReflectionProperty($entity::class, $prop);
        $ref->setValue($entity, $value);
    }

    protected function makeUser(int $id = 1): User
    {
        $user = (new User())
            ->setName('Test')
            ->setUsername('testuser')
            ->setEmail('test@example.com')
            ->setPassword(password_hash('pass', PASSWORD_BCRYPT));
        $this->setProp($user, 'id', $id);
        return $user;
    }

    protected function makeExercise(int $id = 1, string $name = 'Push-up'): Exercise
    {
        $e = (new Exercise())->setName($name)->setDescription('desc')->setIntensity('alta');
        $this->setProp($e, 'id', $id);
        return $e;
    }

    protected function makeRoutine(int $id = 1, string $name = 'Routine A'): Routine
    {
        $r = (new Routine())->setName($name)->setDescription('desc')->setDurationMinutes(45);
        $this->setProp($r, 'id', $id);
        return $r;
    }

    protected function makeRoutineHasExercise(Routine $routine, Exercise $exercise): RoutineHasExercise
    {
        return (new RoutineHasExercise())
            ->setRoutine($routine)
            ->setExercise($exercise)
            ->setSets(3)->setReps(10)->setRestSeconds(60)->setOrderIndex(1);
    }

    protected function makeTrainingSession(int $id, User $user, Routine $routine): TrainingSession
    {
        $s = (new TrainingSession())
            ->setUser($user)
            ->setRoutine($routine)
            ->setDurationMinutes(40)
            ->setCaloriesEstimated(300)
            ->setCreatedAt(new \DateTime('2026-01-01 10:00:00'));
        $this->setProp($s, 'id', $id);
        return $s;
    }

    protected function makeDish(int $id = 1, string $name = 'Salad'): Dish
    {
        $d = (new Dish())->setName($name)->setCaloriesPerServing(200);
        $this->setProp($d, 'id', $id);
        return $d;
    }

    protected function makeMealLog(int $id, User $user, Dish $dish): UserMealLog
    {
        $log = (new UserMealLog())
            ->setUser($user)
            ->setDish($dish)
            ->setQuantity(2)
            ->setCreatedAt(new \DateTime('2026-01-01 12:00:00'));
        $this->setProp($log, 'id', $id);
        return $log;
    }

    protected function makeWeightLog(int $id, User $user, float $kg): UserWeightLog
    {
        $log = (new UserWeightLog())
            ->setUser($user)
            ->setWeightKg($kg)
            ->setCreatedAt(new \DateTime('2026-01-01 08:00:00'));
        $this->setProp($log, 'id', $id);
        return $log;
    }

    protected function makeRequest(?string $jsonBody = null, array $query = [], ?User $user = null): Request
    {
        $request = new Request($query, [], [], [], [], [], $jsonBody);
        if ($user !== null) {
            $request->attributes->set('user', $user);
        }
        return $request;
    }
}
