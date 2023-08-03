<?php

namespace App\Entity;

use App\Repository\ColonyBuildingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColonyBuildingRepository::class)]
class ColonyBuilding
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'buildings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Colony $colony = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $type = null;

    #[ORM\Column]
    private ?int $level = 1;

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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public static array $stats = [
        ['name' => 'Mining Drill', 'prod' => 100, 'prod_type' => 0, 'price' => 500],
        ['name' => 'Hydrocarbon wells', 'prod' => 50, 'prod_type' => 1, 'price' => 750],
        ['name' => 'Spaceport', 'price' => 2000],
        ['name' => 'Radar', 'price' => 1500],
    ];

    public function getProd(): ?int
    {
        if (!isset($this::$stats[$this->type]["prod"])) {
            return null;
        }
        return $this::$stats[$this->type]["prod"] * $this->level;
    }

    public function getProdType(): int
    {
        if (!isset($this::$stats[$this->type]["prod_type"])) {
            return null;
        }
        return $this::$stats[$this->type]["prod_type"];
    }

    public function getName(): string
    {
        return $this::$stats[$this->type]["name"];
    }

    public function getNextLevelPrice(): int
    {
        return pow($this->level + 1, 2) + $this::$stats[$this->type]["price"];
    }
}
