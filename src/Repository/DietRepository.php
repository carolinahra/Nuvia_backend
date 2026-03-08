<?php

namespace App\Repository;

use App\Entity\Diet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Diet>
 *
 * @method Diet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Diet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Diet[]    findAll()
 * @method Diet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DietRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Diet::class);
    }

    public function save(Diet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Diet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
