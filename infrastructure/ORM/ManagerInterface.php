<?php

namespace Infrastructure\ORM;

interface ManagerInterface
{
    public function persist(object $object): void;

    public function remove(object $object): void;

    public function flush(): void;

    public function getRepository(string $className): ?object;
}
