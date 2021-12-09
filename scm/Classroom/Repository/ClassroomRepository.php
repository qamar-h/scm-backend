<?php

namespace SCM\Classroom\Repository;

use SCM\Entity\Classroom;
use Infrastructure\ORM\AbstractRepository;

class ClassroomRepository extends AbstractRepository
{
 protected string  $entityClass =  Classroom::class;
}
