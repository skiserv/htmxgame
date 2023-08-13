<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    public function __construct(
        protected EntityManagerInterface $doctrine,
    ) {
    }

    public function createNotification(
        Player $player,
        int $category,
        string $content,
    ): Notification {
        $nn = new Notification();
        $nn->setPlayer($player);
        $nn->setCategory($category);
        $nn->setContent($content);
        $nn->setCreatedAt(new \DateTimeImmutable());

        $player->setUnreadNotifications($player->getUnreadNotifications() + 1);

        $this->doctrine->persist($nn);
        return $nn;
    }
}
