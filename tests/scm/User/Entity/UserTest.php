<?php

use PHPUnit\Framework\TestCase;
use SCM\User\ValueObject\Person;
use SCM\User\Entity\User;

class UserTest extends TestCase
{
    public function testUserBasicInformations(): void
    {
        $user = new User();
        $user->setEmail('hayat.qamar@gmail.com');

        $this->assertEquals('hayat.qamar@gmail.com', $user->getEmail());
        $this->assertEquals(1, count($user->getRoles()));
    }

    public function testUserPersonInstance(): void
    {
        $user = new User();
        $this->assertInstanceOf(Person::class, $user->getPerson());
    }
}
