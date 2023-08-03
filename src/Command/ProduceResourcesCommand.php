<?php

namespace App\Command;

use App\Entity\ColonyResource;
use App\Repository\ColonyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:produce-resources',
    description: 'Add a short description for your command',
)]
class ProduceResourcesCommand extends Command
{
    public function __construct(
        private ColonyRepository $colonyRepository,
        private EntityManagerInterface $doctrine,
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $io = new SymfonyStyle($input, $output);

        $colonies = $this->colonyRepository->findAll();
        foreach ($colonies as $colony) {
            $io->info('Updating ' . $colony->getName());
            foreach ($colony->getBuildings() as $building) {
                if (!$building->getProd()) {
                    continue;
                }

                $resource = $colony->getRessourceByType($building->getProdType());
                if (!$resource) {
                    $resource = new ColonyResource();
                    $resource->setType($building->getProdType());
                    $colony->addResource($resource);
                    $this->doctrine->persist($resource);
                }

                $r_stock = $resource->getStock();
                $r_stock += $building->getProd();
                $resource->setStock($r_stock);
            }
        }
        $this->doctrine->flush();

        $io->success('Command succeed');

        return Command::SUCCESS;
    }
}
