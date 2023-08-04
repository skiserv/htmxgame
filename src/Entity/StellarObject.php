<?php

namespace App\Entity;

use App\Repository\StellarObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StellarObjectRepository::class)]
class StellarObject
{
    public static int $PROTECTED = 1;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'stellarObjects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?StarSystem $star_system = null;

    #[ORM\OneToMany(mappedBy: 'stellar_object', targetEntity: Colony::class, orphanRemoval: true)]
    private Collection $colonies;

    #[ORM\OneToMany(mappedBy: 'position', targetEntity: Fleet::class)]
    private Collection $fleets;

    #[ORM\OneToMany(mappedBy: 'destination', targetEntity: Fleet::class)]
    private Collection $incoming_fleets;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $special = null;

    #[ORM\Column(nullable: true)]
    private ?bool $colonizable = null;

    public function __construct()
    {
        $this->colonies = new ArrayCollection();
        $this->fleets = new ArrayCollection();
        $this->incoming_fleets = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStarSystem(): ?StarSystem
    {
        return $this->star_system;
    }

    public function setStarSystem(?StarSystem $star_system): static
    {
        $this->star_system = $star_system;

        return $this;
    }

    /**
     * @return Collection<int, Colony>
     */
    public function getColonies(): Collection
    {
        return $this->colonies;
    }

    public function addColony(Colony $colony): static
    {
        if (!$this->colonies->contains($colony)) {
            $this->colonies->add($colony);
            $colony->setStellarObject($this);
        }

        return $this;
    }

    public function removeColony(Colony $colony): static
    {
        if ($this->colonies->removeElement($colony)) {
            // set the owning side to null (unless already changed)
            if ($colony->getStellarObject() === $this) {
                $colony->setStellarObject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fleet>
     */
    public function getFleets(): Collection
    {
        return $this->fleets;
    }

    public function addFleet(Fleet $fleet): static
    {
        if (!$this->fleets->contains($fleet)) {
            $this->fleets->add($fleet);
            $fleet->setPosition($this);
        }

        return $this;
    }

    public function removeFleet(Fleet $fleet): static
    {
        if ($this->fleets->removeElement($fleet)) {
            // set the owning side to null (unless already changed)
            if ($fleet->getPosition() === $this) {
                $fleet->setPosition(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fleet>
     */
    public function getIncomingFleets(): Collection
    {
        return $this->incoming_fleets;
    }

    public function addIncomingFleet(Fleet $incomingFleet): static
    {
        if (!$this->incoming_fleets->contains($incomingFleet)) {
            $this->incoming_fleets->add($incomingFleet);
            $incomingFleet->setDestination($this);
        }

        return $this;
    }

    public function removeIncomingFleet(Fleet $incomingFleet): static
    {
        if ($this->incoming_fleets->removeElement($incomingFleet)) {
            // set the owning side to null (unless already changed)
            if ($incomingFleet->getDestination() === $this) {
                $incomingFleet->setDestination(null);
            }
        }

        return $this;
    }

    public function getSpecial(): ?int
    {
        return $this->special;
    }

    public function setSpecial(?int $special): static
    {
        $this->special = $special;

        return $this;
    }

    public function isColonizable(): ?bool
    {
        return $this->colonizable;
    }

    public function setColonizable(?bool $colonizable): static
    {
        $this->colonizable = $colonizable;

        return $this;
    }
}
