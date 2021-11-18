<?php

namespace SCM\User\Message;

use SCM\User\Entity\User;

class UserPersist
{
    /**
     * @param User $user
     */
    public function __construct(private User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
