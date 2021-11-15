<?php

namespace Infrastructure\Security;

use SCM\User\Entity\User;

interface UserPasswordEncoderInterface
{
    public function encodePassword(User $user, string $password): string;
}
