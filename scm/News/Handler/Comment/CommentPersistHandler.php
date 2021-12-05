<?php

namespace SCM\News\Handler\Comment;

use Infrastructure\Action\HandlerInterface;
use Infrastructure\ORM\ManagerInterface;
use Infrastructure\Security\SecurityInterface;
use SCM\News\Message\Comment\CommentPersist;

class CommentPersistHandler implements HandlerInterface
{
    public function __construct(
        private SecurityInterface $security,
        private ManagerInterface $em
    ) {
    }

    public function __invoke(CommentPersist $message)
    {
        $comment = $message->getComment();

        if ($comment->getId() === null) {
            $comment->setAuthor($this->security->getUser());
        }

        $this->em->persist($comment);
        $this->em->flush();
    }
}
