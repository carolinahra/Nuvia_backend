<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function get(?int $id = null, ?string $email = null, ?int $limit = null, ?int $offset = null): mixed
    {
        if ($id !== null) {
            return $this->findOneById($id);
        }
        if ($email !== null) {
            return $this->findOneByEmail($email);
        }
        return $this->findPaginated($limit, $offset);
    }

    public function save(User $user): int
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
        return $user->getId();
    }

    public function remove(User $user): void
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }

    private function findOneById(int $id): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function findOneByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function findPaginated(?int $limit, ?int $offset): array
    {
        return $this->createQueryBuilder('u')
            ->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset ?? 0)
            ->getResult();
    }
}
