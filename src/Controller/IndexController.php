<?php

namespace App\Controller;

use App\Repository\FleetRepository;
use App\Repository\StarSystemRepository;
use App\Service\PlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(
        StarSystemRepository $starSystemRepository,
        PlayerService $playerService,
        FleetRepository $fleetRepository,
    ): Response {
        if (!$playerService->player) {
            return $this->redirectToRoute('new_player');
        }
        $system_id = 1;
        $system = $starSystemRepository->find($system_id);
        $moving_fleets = $fleetRepository->getMovingBySystemId($system_id);

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'player'        => $playerService->player,
            'system'        => $system,
            'moving_fleets' => $moving_fleets,
        ]);
    }
}
