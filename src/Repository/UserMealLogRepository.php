<?php

namespace App\Repository;

use App\Entity\UserMealLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserMealLog>
 *
 * @method UserMealLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMealLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMealLog[]    findAll()
 * @method UserMealLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMealLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMealLog::class);
    }

    public function save(UserMealLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserMealLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUser(int $userId, ?\DateTimeInterface $from = null, ?\DateTimeInterface $to = null): array
    {
        $qb = $this->createQueryBuilder('uml')
            ->andWhere('uml.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('uml.createdAt', 'DESC');

        if ($from !== null) {
            $qb->andWhere('uml.createdAt >= :from')->setParameter('from', $from);
        }

        if ($to !== null) {
            $qb->andWhere('uml.createdAt <= :to')->setParameter('to', $to);
        }

        return $qb->getQuery()->getResult();
    }
}
