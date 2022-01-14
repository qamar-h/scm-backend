<?php

namespace SCM\News\Message\Post;

use SCM\News\Entity\Post;

class PostPersist
{
    public function __construct(private Post $post)
    {
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
