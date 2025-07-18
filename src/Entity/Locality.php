<?php

namespace App\Entity;

use App\Repository\LocalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocalityRepository::class)]
#[ORM\Index(name: 'IDX_LOCALITY_NAME', columns: ['name'])]
class Locality {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'localities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): static {
        $this->name = $name;

        return $this;
    }

    public function getCity(): ?City {
        return $this->city;
    }

    public function setCity(?City $city): static {
        $this->city = $city;

        return $this;
    }

    public function __toString() {

        return $this->name;
    }
}
