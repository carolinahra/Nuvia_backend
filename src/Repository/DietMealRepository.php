<?php

namespace App\Repository;

use App\Entity\DietMeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DietMeal>
 *
 * @method DietMeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method DietMeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method DietMeal[]    findAll()
 * @method DietMeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DietMealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DietMeal::class);
    }

    public function save(DietMeal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DietMeal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
