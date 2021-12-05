<?php

namespace SCM\Global\Message;

class EntityRemove
{
    public function __construct(private string $className, private int $id)
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getClassName()
    {
        return $this->className;
    }
}
