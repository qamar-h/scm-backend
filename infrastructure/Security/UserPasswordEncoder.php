<?php

namespace Infrastructure\Security;

use SCM\User\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordEncoder implements UserPasswordEncoderInterface
{
    public function __construct(private UserPasswordHasherInterface $encoder)
    {
    }

    public function encodePassword(User $user, string $password): string
    {
        return $this->encoder->hashPassword(
            $user,
            $password
        );
    }
}
