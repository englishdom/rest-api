<?php

namespace User\Entity;

use Common\Entity\EntityInterface;

final class User implements EntityInterface
{
    protected $identifier;
    protected $name;

    public function getId(): int
    {
        return (int)$this->identifier;
    }

    public function setId($identifier): self
    {
        $cloneObject = clone $this;
        $cloneObject->identifier = $identifier;
        return $cloneObject;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $cloneObject = clone $this;
        $cloneObject->name = $name;
        return $cloneObject;
    }
}
