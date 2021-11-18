<?php

use Infrastructure\ORM\EntityManager;
use Infrastructure\Security\Security;
use Infrastructure\Security\UserPasswordEncoder;
use PHPUnit\Framework\TestCase;
use SCM\User\Entity\User;
use SCM\User\Exception\PasswordEmptyException;
use SCM\User\Handler\UserPersistHandler;
use SCM\User\Message\UserPersist;

class UserPersistHandlerTest extends TestCase
{
    public function setup(): void
    {
        $this->encoder = $this->getMockBuilder(UserPasswordEncoder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->security = $this->getMockBuilder(Security::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testPasswordIsEmptyException(): void
    {
        $userPersistHandler = new UserPersistHandler(
            $this->encoder,
            $this->em,
            $this->security
        );

        $userPersist = new UserPersist(
            (new User())->setEmail('fake@email.com')
        );

        $this->expectException(PasswordEmptyException::class);

        $userPersistHandler($userPersist);
    }
}
