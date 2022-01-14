<?php

namespace SCM\News\Handler\Post;

use Infrastructure\Action\HandlerInterface;
use Infrastructure\ORM\ManagerInterface;
use Infrastructure\Security\SecurityInterface;
use SCM\News\Message\Post\PostPersist;
use Infrastructure\Utils\SlugifyInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostPersistHandler implements HandlerInterface
{
    public function __construct(
        private SlugifyInterface $slugify,
        private ManagerInterface $em,
        private SecurityInterface $security
    ) {
    }

    public function __invoke(PostPersist $message)
    {
        $post = $message->getPost();

        if ($post->getId() === null) {
            $post->setSlug(
                $this->slugify->execute($post->getTitle() ?? '')
            );

            $post->setAuthor($this->security->getUser());
        }

        $this->em->persist($post);
        $this->em->flush();
    }
}
