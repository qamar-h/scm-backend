<?php

namespace App\Fixtures\Providers;

use SCM\User\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherProvider
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function passwordHasher(string $plainPassword): string
    {
        return $this->encoder->hashPassword(new User(), $plainPassword);
    }
}
