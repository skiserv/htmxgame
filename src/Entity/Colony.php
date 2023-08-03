<?php

namespace App\Entity;

use App\Repository\ColonyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColonyRepository::class)]
class Colony
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'colonies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?StellarObject $stellar_object = null;

    #[ORM\ManyToOne(inversedBy: 'colonies')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Player $player = null;

    #[ORM\OneToMany(mappedBy: 'colony', targetEntity: ColonyBuilding::class, orphanRemoval: true)]
    private Collection $buildings;

    #[ORM\OneToMany(mappedBy: 'colony', targetEntity: ColonyResource::class, orphanRemoval: true)]
    private Collection $resources;

    public function __construct()
    {
        $this->buildings = new ArrayCollection();
        $this->resources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStellarObject(): ?StellarObject
    {
        return $this->stellar_object;
    }

    public function setStellarObject(?StellarObject $stellar_object): static
    {
        $this->stellar_object = $stellar_object;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    /**
     * @return Collection<int, ColonyBuilding>
     */
    public function getBuildings(): Collection
    {
        return $this->buildings;
    }

    public function getBuildingByType(int $type): ?ColonyBuilding
    {
        foreach ($this->buildings as $building) {
            if ($building->getType() == $type) {
                return $building;
            }
        }
        return null;
    }

    public function addBuilding(ColonyBuilding $building): static
    {
        if (!$this->buildings->contains($building)) {
            $this->buildings->add($building);
            $building->setColony($this);
        }

        return $this;
    }

    public function removeBuilding(ColonyBuilding $building): static
    {
        if ($this->buildings->removeElement($building)) {
            // set the owning side to null (unless already changed)
            if ($building->getColony() === $this) {
                $building->setColony(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ColonyResource>
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function getRessourceByType(int $type): ?ColonyResource
    {
        foreach ($this->resources as $resource) {
            if ($resource->getType() == $type) {
                return $resource;
            }
        }
        return null;
    }

    public function addResource(ColonyResource $resource): static
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
            $resource->setColony($this);
        }

        return $this;
    }

    public function removeResource(ColonyResource $resource): static
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getColony() === $this) {
                $resource->setColony(null);
            }
        }

        return $this;
    }
}
