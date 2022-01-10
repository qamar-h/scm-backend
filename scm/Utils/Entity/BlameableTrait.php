<?php

namespace SCM\Utils\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait BlameableTrait
{
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    #[Groups(["user:get", "post:get"])]
    private ?string $createdBy;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    #[Groups(["user:get", "post:get"])]
    private ?string $updatedBy;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    #[Groups(["user:get", "post:get"])]
    private ?string $deletedBy;

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getDeletedBy(): ?string
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(string $deletedBy): self
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }
}
