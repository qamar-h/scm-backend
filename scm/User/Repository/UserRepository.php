<?php

namespace SCM\User\Repository;

use Infrastructure\ORM\AbstractRepository;
use SCM\User\Entity\User;

class UserRepository extends AbstractRepository
{
    protected string $entityClass = User::class;
}
