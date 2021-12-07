<?php

namespace SCM\News\Repository;

use Infrastructure\ORM\AbstractRepository;
use SCM\News\Entity\Post;

class PostRepository extends AbstractRepository
{
    protected string $entityClass = Post::class;
}
