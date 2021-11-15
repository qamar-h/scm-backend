<?php

namespace Infrastructure\ORM;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository implements RepositoryInterface
{
    protected string $entityClass;

    public function __construct(ManagerRegistry $registry)
    {
        if (null == $this->entityClass) {
            throw new \Exception('No entity specified!');
        }

        parent::__construct($registry, $this->entityClass);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * @return array<object>|null
     */
    public function findAll(): ?array
    {
        return parent::findAll();
    }

    /**
     * @return array<object>|null
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): ?array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}
