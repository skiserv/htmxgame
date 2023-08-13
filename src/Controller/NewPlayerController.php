<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\NewPlayerType;
use App\Repository\StellarObjectRepository;
use App\Service\NewColonyService;
use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use NotificationService;
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
        NewColonyService $newColonyService,
        NotificationService $notificationService,
    ) {
        if ($playerService->player) {
            return $this->redirectToRoute('app_index');
        }
        $new_player = new Player();
        $form = $this->createForm(NewPlayerType::class, $new_player);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $new_player = $form->getData();
            $playerService->user->setPlayer($new_player);
            $doctrine->persist($new_player);

            $playerService->player = $new_player;

            $start_planet = $stellarObjectRepository->findOneBy(['special' => 1]);
            $newColonyService->createColony(object: $start_planet);

            $notificationService->createNotification($new_player, 0, "Welcome on the game !");

            $doctrine->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('new_player/index.html.twig', [
            'new_player_form' => $form,
        ]);
    }
}
