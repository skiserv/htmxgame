<?php

namespace App\Controller;

use App\Repository\StellarObjectRepository;
use App\Service\PlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class StellarObjectController extends AbstractController
{
    #[Route(
        '/stellar-object/{id}',
        name: 'stellar_object',
        methods: ['GET']
    )]
    public function stellarObject(
        int $id,
        StellarObjectRepository $stellarObjectRepository,
        PlayerService $playerService,
    ): Response {
        $object = $stellarObjectRepository->find($id);
        if (!$object) {
            throw new NotFoundHttpException();
        }
        return $this->render('stellar_object/stellar_object.html.twig', [
            'object' => $object,
            'player' => $playerService->player,
        ]);
    }
}
