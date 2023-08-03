<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\SecurityBundle\Security;

class PlayerService
{
    public User $user;
    public ?Player $player;

    public function __construct(
        RequestStack $requestStack,
        Security $security,
    ) {
        $session = $requestStack->getSession();

        $this->user = $security->getUser();
        $this->player = $this->user->getPlayer();
    }
}
