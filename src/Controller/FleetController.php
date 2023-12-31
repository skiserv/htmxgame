<?php

namespace App\Controller;

use App\Entity\Fleet;
use App\Form\FleetEditType;
use App\Form\FleetTravelType;
use App\Repository\FleetRepository;
use App\Service\NewColonyService;
use App\Service\PlayerService;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FleetController extends AbstractController
{
    #[Route('/fleet', name: 'fleets')]
    public function fleets(PlayerService $playerService): Response
    {
        return $this->render('fleet/fleets.html.twig', [
            'fleets' => $playerService->player->getFleets(),
        ]);
    }

    #[Route(
        '/fleet/{id}',
        name: 'fleet',
        methods: ['GET'],
    )]
    public function getFleet(
        int $id,
        FleetRepository $fleetRepository,
        PlayerService $playerService,
    ): Response {
        $fleet = $fleetRepository->find($id);
        if (!$fleet || $playerService->player != $fleet->getPlayer()) {
            throw new NotFoundHttpException();
        }
        $travel_form = $this->createForm(FleetTravelType::class);
        return $this->render('fleet/fleet.html.twig', [
            'fleet' => $fleet,
            'detailed' => true,
            'travel_form' => $travel_form,
        ]);
    }

    #[Route(
        'fleet/{id}/travel',
        name: 'fleet_travel',
        methods: ['POST'],
    )]
    public function travel(
        int $id,
        Request $request,
        FleetRepository $fleetRepository,
        PlayerService $playerService,
        EntityManagerInterface $doctrine,
    ): Response {
        $fleet = $fleetRepository->find($id);
        if (!$fleet || $playerService->player != $fleet->getPlayer()) {
            throw new NotFoundHttpException();
        }
        $travel_form = $this->createForm(FleetTravelType::class);
        $travel_form->handleRequest($request);
        if ($travel_form->isSubmitted() && $travel_form->isValid()) {

            # ToDo : check right system

            $fleet->setDestination($travel_form->getData()["destination"]);
            $arrives_at = (new DateTime())->add(DateInterval::createFromDateString("1 minute"));
            $fleet->setActionEnd($arrives_at);
            $doctrine->flush();
        }

        # ToDo : also return fragment to update moving fleets of the system
        $moving_fleets = $fleetRepository->getMovingBySystemId($fleet->getPosition()->getStarSystem()->getId());

        return $this->render('fleet/action/travel.html.twig', [
            'fleet'         => $fleet,
            'moving_fleets' => $moving_fleets,
            'player'        => $playerService->player,
        ]);
    }


    #[Route(
        'fleet/{id}/edit',
        name: 'fleet_edit',
        methods: ['POST', 'GET'],
    )]
    public function rename(
        int $id,
        Request $request,
        FleetRepository $fleetRepository,
        PlayerService $playerService,
        EntityManagerInterface $doctrine,
    ): Response {
        $fleet = $fleetRepository->find($id);
        if (!$fleet || $playerService->player != $fleet->getPlayer()) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(FleetEditType::class, $fleet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fleet = $form->getData();
            $doctrine->flush();
            return $this->render(
                'fleet/fleet_details.html.twig',
                ['fleet' => $fleet]
            );
        } else {
            return $this->render(
                'fleet/fleet_details_edit.html.twig',
                ['form' => $form, 'fleet' => $fleet],
            );
        }
    }

    #[Route(
        '/fleet/{id}/absorb/{victim_id}',
        name: 'fleet_absorb',
        methods: ['PUT']
    )]
    public function absorbFleet(
        int $id,
        int $victim_id,
        FleetRepository $fleetRepository,
        PlayerService $playerService,
        EntityManagerInterface $doctrine,
    ): Response {
        $fleet = $fleetRepository->find($id);
        if (!$fleet || $playerService->player != $fleet->getPlayer()) {
            throw new NotFoundHttpException();
        }

        # ToDo : fleed should not be moving

        $victim  =  $fleetRepository->find($victim_id);
        if (!$victim || $playerService->player != $victim->getPlayer()) {
            throw new NotFoundHttpException();
        }
        foreach ($victim->getShips() as $ship) {
            $victim->removeShip($ship);
            $fleet->addShip($ship);
        }
        $doctrine->remove($victim);
        $doctrine->flush();

        return $this->render("fleet/action/absorb.html.twig", [
            'ships' => $fleet->getShips(), 'fleet' => $fleet,
        ]);
    }

    #[Route(
        '/fleet/{id}/split/{ship_id}',
        name: 'fleet_split',
        methods: ['PUT'],
    )]
    public function splitFleet(
        int $id,
        int $ship_id,
        FleetRepository $fleetRepository,
        PlayerService $playerService,
        EntityManagerInterface $doctrine,
    ): Response {
        $fleet = $fleetRepository->find($id);
        if (!$fleet || $playerService->player != $fleet->getPlayer()) {
            throw new NotFoundHttpException();
        }

        # ToDo : fleed should not be moving

        if (count($fleet->getShips()) < 2) {
            throw new HttpException(400);
        }

        $ship = null;
        foreach ($fleet->getShips() as $s) {
            if ($s->getId() == $ship_id) {
                $ship = $s;
            }
        }

        if (!$ship) {
            throw new NotFoundHttpException();
        }

        $new_fleet = new Fleet();
        $new_fleet->addShip($ship);
        $new_fleet->setName('New Fleet');
        $fleet->getPosition()->addFleet($new_fleet);
        $playerService->player->addFleet($new_fleet);

        $doctrine->persist($ship);
        $doctrine->persist($new_fleet);
        $doctrine->flush();

        return $this->render(
            'fleet/action/split.html.twig',
            ['other_fleet' => $new_fleet, 'fleet' => $fleet],
        );
    }

    #[Route(
        '/fleet/{id}/colonize/',
        name: 'fleet_colonize',
        methods: ['POST'],
    )]
    public function colonize(
        int $id,
        FleetRepository $fleetRepository,
        PlayerService $playerService,
        NewColonyService $newColonyService,
        EntityManagerInterface $doctrine,
    ): Response {
        $fleet = $fleetRepository->find($id);
        if (!$fleet || $playerService->player != $fleet->getPlayer()) {
            throw new NotFoundHttpException();
        }

        if (!$fleet->getPosition()->isColonizable()) {
            throw new BadRequestHttpException("Not colonizable");
        }

        foreach ($fleet->getPosition()->getColonies() as $colony) {
            if ($colony->getPlayer() == $playerService->player) {
                throw new BadRequestHttpException("Player already have a colony here");
            }
        }

        $found_colo = false;
        foreach ($fleet->getShips() as $ship) {
            if ($ship->isColony()) {
                $fleet->removeShip($ship);
                $doctrine->remove($ship);
                $found_colo = true;
                continue;
            }
        }

        if (!$found_colo) {
            throw new BadRequestHttpException("Missing colony ship in the fleet");
        }

        if (!$fleet->getShips()->count()) {
            $doctrine->remove($fleet);
        }

        $new_colony = $newColonyService->createColony($fleet->getPosition());

        $doctrine->flush();

        return $this->render(
            'fleet/action/colonize.html.twig',
            ['new_colony' => $new_colony],
        );
    }
}
