<?php

namespace App\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Infrastructure\Security\SecurityInterface;

class DoctrineEntity implements EventSubscriber
{
    public function __construct(private SecurityInterface $security)
    {
    }

    public function getSubscribedEvents(): array
    {
        return ['prePersist'];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity === null) {
            return;
        }

        if ($entity->getId() === null && method_exists($entity, 'setCreatedAt')) {
            $entity
                ->setCreatedAt(new \DateTimeImmutable())
                ->setCreatedBy($this->currentUserFullName());
        }

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setUpdatedBy($this->currentUserFullName());
        }
    }

    private function currentUserFullName(): string
    {
        return $this->security->getUser() !== null ?
        $this->security->getUser()->getPerson()->getFullname() : '';
    }
}
