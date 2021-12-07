<?php

namespace SCM\Global\Handler;

use DateTimeImmutable;
use Infrastructure\Action\HandlerInterface;
use Infrastructure\ORM\ManagerInterface;
use Infrastructure\Security\SecurityInterface;
use SCM\Global\Exception\EntityInstanceNotFoundException;
use SCM\Global\Message\EntityRemove;

class EntityRemoveHandler implements HandlerInterface
{
    public function __construct(
        private ManagerInterface $em,
        private SecurityInterface $security
    ) {
    }

    public function __invoke(EntityRemove $message)
    {
        $repository = $this->em->getRepository($message->getClassName());

        if (null == $entity = $repository->find($message->getId())) {
            throw new EntityInstanceNotFoundException($message->getClassName() . ' not found.');
        }

        if (method_exists($entity, 'setDeletedAt')) {
            $entity->setDeletedAt(new DateTimeImmutable());
        }

        if (method_exists($entity, 'setDeletedBy')) {
            $entity->setDeletedBy(
                $this->security->getUser() !== null ?
                $this->security->getUser()->getPerson()->getFullname() : ''
            );
        }

        $this->em->persist($entity);
        $this->em->flush();
    }
}
