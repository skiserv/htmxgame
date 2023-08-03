<?php

namespace App\Command;

use App\Repository\FleetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fleet-finish-action',
    description: 'Add a short description for your command',
)]
class FleetFinishActionCommand extends Command
{
    public function __construct(
        private FleetRepository $fleetRepository,
        private EntityManagerInterface $doctrine,
    ) {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fleets = $this->fleetRepository->getFinishedAction();
        foreach ($fleets as $fleet) {
            $io->info("Moving " . $fleet->getName());
            $fleet->setPosition($fleet->getDestination());
            $fleet->setDestination(null);
            $fleet->setActionEnd(null);
        }
        $this->doctrine->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
