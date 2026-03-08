<?php

namespace App\Repository;

use App\Entity\RoutineHasExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoutineHasExercise>
 *
 * @method RoutineHasExercise|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoutineHasExercise|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoutineHasExercise[]    findAll()
 * @method RoutineHasExercise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoutineHasExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoutineHasExercise::class);
    }

    public function save(RoutineHasExercise $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RoutineHasExercise $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
