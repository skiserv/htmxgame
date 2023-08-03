<?php

namespace App\Entity;

use App\Repository\FleetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FleetRepository::class)]
class Fleet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'fleets')]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'fleets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?StellarObject $position = null;

    #[ORM\OneToMany(mappedBy: 'fleet', targetEntity: Ship::class, orphanRemoval: true)]
    private Collection $ships;

    #[ORM\ManyToOne(inversedBy: 'incoming_fleets')]
    private ?StellarObject $destination = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $action_end = null;

    public function __construct()
    {
        $this->ships = new ArrayCollection();
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

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getPosition(): ?StellarObject
    {
        return $this->position;
    }

    public function setPosition(?StellarObject $position): static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection<int, Ship>
     */
    public function getShips(): Collection
    {
        return $this->ships;
    }

    public function addShip(Ship $ship): static
    {
        if (!$this->ships->contains($ship)) {
            $this->ships->add($ship);
            $ship->setFleet($this);
        }

        return $this;
    }

    public function removeShip(Ship $ship): static
    {
        if ($this->ships->removeElement($ship)) {
            // set the owning side to null (unless already changed)
            if ($ship->getFleet() === $this) {
                $ship->setFleet(null);
            }
        }

        return $this;
    }

    public function getDestination(): ?StellarObject
    {
        return $this->destination;
    }

    public function setDestination(?StellarObject $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getActionEnd(): ?\DateTimeInterface
    {
        return $this->action_end;
    }

    public function setActionEnd(?\DateTimeInterface $action_end): static
    {
        $this->action_end = $action_end;

        return $this;
    }

    public function canActOrMove(): bool
    {
        # ToDo : finish
        if ($this->destination) {
            return false;
        }
        return true;
    }
}
