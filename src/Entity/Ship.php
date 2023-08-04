<?php

namespace App\Entity;

use App\Repository\ShipRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShipRepository::class)]
class Ship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $type = null;

    #[ORM\ManyToOne(inversedBy: 'ships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fleet $fleet = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFleet(): ?Fleet
    {
        return $this->fleet;
    }

    public function setFleet(?Fleet $fleet): static
    {
        $this->fleet = $fleet;

        return $this;
    }

    public static array $stats = [
        [
            'name'  => 'Drone',
            'price' => [1000, 5],
            'speed' => 1000,
        ],
        [
            'name'  => 'Frigate',
            'price' => [5000, 1000],
            'speed' => 800,
        ],
        [
            'name'  => 'Cruiser',
            'price' => [10000, 2000],
            'speed' => 750,
        ],
        [
            'name'  => 'Colony Ship',
            'price' => [50000, 10000],
            'speed' => 100,
            'colony' => true,
        ]
    ];

    public function getName(): string
    {
        return $this::$stats[$this->type]["name"];
    }

    public function getPrice(): int
    {
        return $this::$stats[$this->type]["price"];
    }

    public function isColony(): bool
    {
        if (!isset($this::$stats[$this->type]["colony"])) {
            return false;
        }
        return $this::$stats[$this->type]["colony"];
    }
}
