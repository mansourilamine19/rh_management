<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ORM\Index(name: 'IDX_REGION_NAME', columns: ['name'])]
#[ORM\Index(name: 'IDX_REGION_CODE', columns: ['code'])]
class Region {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 9)]
    private ?string $code = null;

    /**
     * @var Collection<int, City>
     */
    #[ORM\OneToMany(targetEntity: City::class, mappedBy: 'regionId')]
    private Collection $cities;

    public function __construct() {
        $this->cities = new ArrayCollection();
    }

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

    public function getCode(): ?string {
        return $this->code;
    }

    public function setCode(string $code): static {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, City>
     */
    public function getCities(): Collection {
        return $this->cities;
    }

    public function addCity(City $city): static {
        if (!$this->cities->contains($city)) {
            $this->cities->add($city);
            $city->setRegionId($this);
        }

        return $this;
    }

    public function removeCity(City $city): static {
        if ($this->cities->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getRegionId() === $this) {
                $city->setRegionId(null);
            }
        }

        return $this;
    }

    public function __toString(): string {

        return $this->name;
    }
}
