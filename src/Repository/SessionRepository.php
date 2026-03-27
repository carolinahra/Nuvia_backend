<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function get(?int $id = null, ?string $token = null): mixed
    {
        if ($id !== null) {
            return $this->findOneById($id);
        }
        if ($token !== null) {
            return $this->findOneByToken($token);
        }
        return null;
    }

    public function save(Session $session): int
    {
        $em = $this->getEntityManager();
        $em->persist($session);
        $em->flush();
        return $session->getId();
    }

    public function remove(Session $session): void
    {
        $em = $this->getEntityManager();
        $em->remove($session);
        $em->flush();
    }

    private function findOneById(int $id): ?Session
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function findOneByToken(string $token): ?Session
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
