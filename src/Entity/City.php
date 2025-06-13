<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ORM\Index(name: 'IDX_CITY_NAME', columns: ['name'])]
class City {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'cities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

    /**
     * @var Collection<int, Locality>
     */
    #[ORM\OneToMany(targetEntity: Locality::class, mappedBy: 'city')]
    private Collection $localities;

    public function __construct() {
        $this->localities = new ArrayCollection();
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

    public function getRegion(): ?Region {
        return $this->region;
    }

    public function setRegion(?Region $region): static {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection<int, Locality>
     */
    public function getLocalities(): Collection {
        return $this->localities;
    }

    public function addLocality(Locality $locality): static {
        if (!$this->localities->contains($locality)) {
            $this->localities->add($locality);
            $locality->setCity($this);
        }

        return $this;
    }

    public function removeLocality(Locality $locality): static {
        if ($this->localities->removeElement($locality)) {
            // set the owning side to null (unless already changed)
            if ($locality->getCity() === $this) {
                $locality->setCity(null);
            }
        }

        return $this;
    }

    public function __toString() {
        
        return $this->name;
    }
}
