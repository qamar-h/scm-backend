<?php

namespace Infrastructure\ORM;

interface RepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findOneBy(array $criteria, array $orderBy = null);
    
    public function findAll();
    
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
}