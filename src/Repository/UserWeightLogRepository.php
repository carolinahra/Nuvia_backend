<?php

namespace App\Repository;

use App\Entity\UserWeightLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserWeightLog>
 *
 * @method UserWeightLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserWeightLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserWeightLog[]    findAll()
 * @method UserWeightLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserWeightLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserWeightLog::class);
    }

    public function save(UserWeightLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserWeightLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
