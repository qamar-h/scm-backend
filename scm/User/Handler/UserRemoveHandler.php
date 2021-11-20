<?php

namespace SCM\User\Handler;

use Infrastructure\Action\HandlerInterface;
use Infrastructure\ORM\ManagerInterface;
use Infrastructure\Security\SecurityInterface;
use SCM\User\Exception\UserNotFoundException;
use SCM\User\Message\UserRemove;
use SCM\User\Repository\UserRepository;
use DateTimeImmutable;

class UserRemoveHandler implements HandlerInterface
{
    public function __construct(
        private UserRepository $repository,
        private ManagerInterface $em,
        private SecurityInterface $security
    ) {
    }

    public function __invoke(UserRemove $message): void
    {
        if (null === $user = $this->repository->find($message->getUserId())) {
            throw new UserNotFoundException('User not found');
        }

        $user->setDeletedAt(new DateTimeImmutable());
        $user->setDeletedBy(
            $this->security->getUser() !== null ?
            $this->security->getUser()->getPerson()->getFullname() : ''
        );

        $this->em->flush();
    }
}
