<?php

namespace Infrastructure\Security;

use SCM\User\Entity\User;

interface SecurityInterface
{
    public function getUser(): ?User;
}
