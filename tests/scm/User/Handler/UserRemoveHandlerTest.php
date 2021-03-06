<?php

use Infrastructure\ORM\EntityManager;
use Infrastructure\Security\Security;
use PHPUnit\Framework\TestCase;
use SCM\User\Exception\UserNotFoundException;
use SCM\User\Repository\UserRepository;
use SCM\User\Handler\UserRemoveHandler;
use SCM\User\Message\UserRemove;

class UserRemoveHandlerTest extends TestCase
{
    public function setup(): void
    {
        $this->repository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->security = $this->getMockBuilder(Security::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testUserNotFoundException(): void
    {
        $this->repository->method('find')->willReturn(null);

        $userDeletionHandler = new UserRemoveHandler(
            $this->repository,
            $this->em,
            $this->security
        );

        $this->expectException(UserNotFoundException::class);

        $userDeletionHandler(new UserRemove(100));
    }
}
