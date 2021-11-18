<?php

namespace Infrastructure\Security;

use SCM\User\Entity\User;
use Symfony\Component\Security\Core\Security as SymfonySecurity;

class Security implements SecurityInterface
{
    public function __construct(private SymfonySecurity $security)
    {
    }

    public function getUser(): ?User
    {
        if ($this->security->getUser() instanceof User) {
            return $this->security->getUser();
        }

        return null;
    }
}
