<?php

namespace App\Controller;

use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankingController extends AbstractController
{

    #[Route('/ranking', name: 'ranking')]
    public function ranking(
        PlayerRepository $playerRepository
    ): Response {
        $players = $playerRepository->getPlayersByColonyNumber();
        return $this->render('ranking/index.html.twig', [
            'players' => $players,
        ]);
    }
}
