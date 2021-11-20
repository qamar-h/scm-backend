<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Infrastructure\Security\UserPasswordEncoder;
use SCM\User\Entity\User;
use DateTimeImmutable;
use DateTime;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordEncoder $userPasswordEncoder)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = (new User())
            ->setEmail('admin@scm.fr')
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTimeImmutable())
            ->setCreatedBy('fixtures')
            ->setUpdatedBy('fixtures');

        $password = $this->userPasswordEncoder->encodePassword($admin, 'admin');
        $admin->setPassword($password);
        $admin->getPerson()
            ->setLastname('Hayat')
            ->setFirstname('Qamar')
            ->setDateOfBirthday(new DateTime('1987-12-26'))
            ->setGender(true);

        $manager->persist($admin);
        $manager->flush();
    }
}
