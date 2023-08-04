<?php

namespace App\Service;

use App\Entity\Colony;
use App\Entity\ColonyBuilding;
use App\Entity\StellarObject;
use Doctrine\ORM\EntityManagerInterface;

class NewColonyService
{
    public function __construct(
        protected EntityManagerInterface $doctrine,
        protected PlayerService $playerService,
    ) {
    }

    public function createColony(
        StellarObject $object,
    ): Colony {
        $colony = new Colony();
        $colony->setName($this->playerService->player->getName() . " colony");
        $colony->setPlayer($this->playerService->player);
        $colony->setStellarObject($object);
        $mine = new ColonyBuilding();
        $mine->setType(0);
        $colony->addBuilding($mine);

        $this->doctrine->persist($mine);
        $this->doctrine->persist($colony);

        return $colony;
    }
}
