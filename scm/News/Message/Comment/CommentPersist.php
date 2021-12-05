<?php

namespace SCM\News\Message\Comment;

use SCM\News\Entity\Comment;

class CommentPersist
{
    public function __construct(private Comment $comment)
    {
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }
}
