<?php

namespace App\Controller;

use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notifications', name: 'notifications', methods: ['GET'])]
    public function notifications(
        PlayerService $playerService,
        EntityManagerInterface $doctrine,
    ): Response {
        $playerService->player->setUnreadNotifications(0);
        $doctrine->persist($playerService->player);
        $doctrine->flush();


        return $this->render(
            'notification/notifications_list.html.twig',
            ['notifs' => $playerService->player->getNotifications()],
        );
    }
}
