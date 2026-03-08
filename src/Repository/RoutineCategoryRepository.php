<?php

namespace App\Repository;

use App\Entity\RoutineCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoutineCategory>
 *
 * @method RoutineCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoutineCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoutineCategory[]    findAll()
 * @method RoutineCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoutineCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoutineCategory::class);
    }

    public function save(RoutineCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RoutineCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
