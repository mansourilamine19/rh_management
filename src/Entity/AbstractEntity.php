<?php

namespace App\Entity;

/**
 * Description of AbstractEntity
 *
 * @author Lamine Mansouri <mansourilamine19@gmail.com>
 */
use Doctrine\ORM\Mapping as ORM;

trait AbstractEntity {

    #[ORM\Column(nullable: true)]
    public ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "created_by", nullable: true)]
    public ?User $createdBy = null;

    #[ORM\Column(nullable: true)]
    public ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "updated_by", nullable: true)]
    public ?User $updatedBy = null;

    #[ORM\Column(nullable: true)]
    public ?\DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "deleted_by", nullable: true)]
    public ?User $deletedBy = null;

    public function getCreatedAt(): ?\DateTimeImmutable {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?User {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): static {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedBy(): ?User {
        return $this->updatedBy;
    }

    public function setUpdatedBy(User $updatedBy): static {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist() {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate() {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getDeletedAt(): ?\DateTimeImmutable {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeImmutable $deletedAt): static {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getDeletedBy(): ?User {
        return $this->deletedBy;
    }

    public function setDeletedBy(User $deletedBy): static {
        $this->deletedBy = $deletedBy;

        return $this;
    }
}
