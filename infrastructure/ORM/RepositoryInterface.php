<?php

namespace Infrastructure\ORM;

interface RepositoryInterface
{
    /**
     *
     * @param integer $id
     * @param mixed $lockMode
     * @param mixed $lockVersion
     * @return object|null
     */
    public function find(int $id, mixed $lockMode = null, mixed $lockVersion = null): ?object;

    /**
     *
     * @param array<string, int> $criteria
     * @param array<string, int>|null $orderBy
     * @return object|null
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?object;

    /**
     * @return array<object>|null
     */
    public function findAll(): ?array;

    /**
     * @param array<string, int> $criteria
     * @param array<string, int>|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array<object>|null
     */
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array;
}
