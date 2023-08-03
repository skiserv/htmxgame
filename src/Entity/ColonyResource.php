<?php

namespace App\Entity;

use App\Repository\ColonyResourceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColonyResourceRepository::class)]
class ColonyResource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Colony $colony = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $type = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $stock = "0";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColony(): ?Colony
    {
        return $this->colony;
    }

    public function setColony(?Colony $colony): static
    {
        $this->colony = $colony;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStock(): ?int
    {
        return intval($this->stock);
    }

    public function setStock(int $stock): static
    {
        $this->stock = strval($stock);

        return $this;
    }

    private array $stats = ["Metal", "Hydrocarbon"];
    public function getName(): string
    {
        return $this->stats[$this->type];
    }
}
