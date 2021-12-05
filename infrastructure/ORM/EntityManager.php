<?php

namespace Infrastructure\ORM;

use Doctrine\ORM\EntityManagerInterface;

class EntityManager implements ManagerInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function persist(object $object): void
    {
        $this->em->persist($object);
    }

    public function remove(object $object): void
    {
        $this->em->remove($object);
    }

    public function flush(): void
    {
        $this->em->flush();
    }

    public function getRepository(string $className): ?object
    {
        return $this->em->getRepository($className);
    }
}
