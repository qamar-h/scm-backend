<?php

namespace SCM\News\Repository;

use Infrastructure\ORM\AbstractRepository;
use SCM\News\Entity\Comment;

class CommentRepository extends AbstractRepository
{
    protected string $entityClass = Comment::class;
}
