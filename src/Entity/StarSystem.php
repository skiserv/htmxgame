<?php

namespace App\Entity;

use App\Repository\StarSystemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StarSystemRepository::class)]
class StarSystem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'star_system', targetEntity: StellarObject::class, orphanRemoval: true)]
    private Collection $stellarObjects;

    public function __construct()
    {
        $this->stellarObjects = new ArrayCollection();
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

    /**
     * @return Collection<int, StellarObject>
     */
    public function getStellarObjects(): Collection
    {
        return $this->stellarObjects;
    }

    public function addStellarObject(StellarObject $stellarObject): static
    {
        if (!$this->stellarObjects->contains($stellarObject)) {
            $this->stellarObjects->add($stellarObject);
            $stellarObject->setStarSystem($this);
        }

        return $this;
    }

    public function removeStellarObject(StellarObject $stellarObject): static
    {
        if ($this->stellarObjects->removeElement($stellarObject)) {
            // set the owning side to null (unless already changed)
            if ($stellarObject->getStarSystem() === $this) {
                $stellarObject->setStarSystem(null);
            }
        }

        return $this;
    }
}
