<?php

namespace App\Controller;

use App\Entity\Colony;
use App\Entity\ColonyBuilding;
use App\Entity\Player;
use App\Form\NewPlayerType;
use App\Repository\StellarObjectRepository;
use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewPlayerController extends AbstractController
{
    #[Route('/new', name: 'new_player')]
    public function newPlayer(
        PlayerService $playerService,
        Request $request,
        EntityManagerInterface $doctrine,
        StellarObjectRepository $stellarObjectRepository,
    ) {
        if ($playerService->player) {
            return $this->redirectToRoute('app_index');
        }
        $new_player = new Player();
        $form = $this->createForm(NewPlayerType::class, $new_player);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $playerService->user->setPlayer($new_player);
            $new_player = $form->getData();
            $doctrine->persist($new_player);

            $start_planet = $stellarObjectRepository->findOneBy(['special' => 1]);
            $colony = new Colony();
            $colony->setName($new_player->getName() . " colony");
            $colony->setPlayer($new_player);
            $colony->setStellarObject($start_planet);
            $mine = new ColonyBuilding();
            $mine->setType(0);
            $colony->addBuilding($mine);
            $doctrine->persist($mine);
            $doctrine->persist($colony);

            $doctrine->flush();
            return $this->redirectToRoute('app_index');
        }

        return $this->render('new_player/index.html.twig', [
            'new_player_form' => $form,
        ]);
    }
}
