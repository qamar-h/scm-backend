<?php

namespace App\fixtures\Providers;

use SCM\User\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HashPasswordProvider
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function hashPassword(string $plainPassword): string
    {
        return $this->encoder->hashPassword(new User(), $plainPassword);
    }
}
