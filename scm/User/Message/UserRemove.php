<?php

namespace SCM\User\Message;

class UserRemove
{
    /**
     * @param int $userId
     */
    public function __construct(private int $userId)
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
