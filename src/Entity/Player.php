<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToOne(inversedBy: 'player', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Colony::class)]
    private Collection $colonies;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Fleet::class)]
    private Collection $fleets;

    public function __construct()
    {
        $this->colonies = new ArrayCollection();
        $this->fleets = new ArrayCollection();
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

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
            $colony->setPlayer($this);
        }

        return $this;
    }

    public function removeColony(Colony $colony): static
    {
        if ($this->colonies->removeElement($colony)) {
            // set the owning side to null (unless already changed)
            if ($colony->getPlayer() === $this) {
                $colony->setPlayer(null);
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
            $fleet->setPlayer($this);
        }

        return $this;
    }

    public function removeFleet(Fleet $fleet): static
    {
        if ($this->fleets->removeElement($fleet)) {
            // set the owning side to null (unless already changed)
            if ($fleet->getPlayer() === $this) {
                $fleet->setPlayer(null);
            }
        }

        return $this;
    }
}
