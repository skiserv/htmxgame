<?php

namespace App\Controller;

use App\Entity\ColonyBuilding;
use App\Entity\Fleet;
use App\Entity\Ship;
use App\Form\ColonyNameEdit;
use App\Form\NewBuildingType;
use App\Form\NewShipType;
use App\Repository\ColonyRepository;
use App\Service\PlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ColonyController extends AbstractController
{
    #[Route('/colony', name: 'colonies')]
    public function colonies(
        PlayerService $playerService,
    ): Response {
        return $this->render('colony/colonies.html.twig', [
            'colonies' => $playerService->player->getColonies(),
        ]);
    }

    #[Route('/colony/{id}', name: 'colony')]
    public function colony(
        int $id,
        PlayerService $playerService,
        ColonyRepository $colonyRepository,
    ): Response {
        $colony = $colonyRepository->find($id);
        if ($colony->getPlayer() != $playerService->player) {
            throw new NotFoundHttpException();
        }

        $new_building_form = $this->createForm(
            NewBuildingType::class,
            null,
            [
                'colony_buildings' => $colony->getBuildings(),
                'attr' => [
                    'hx-post'   => $this->generateUrl('building_create', ['colony_id' => $id]),
                    'hx-target' => '#buildings',
                    'hx-swap'   => 'beforeend',
                    'hx-trigger' => 'submit',
                ]
            ],
        );

        $new_ship_form = $this->createForm(
            NewShipType::class,
            null,
            [
                'attr' => [
                    'hx-post'   => $this->generateUrl('ship_create', ['colony_id' => $id]),
                    'hx-target' => '#my_fleets',
                    'hx-swap'   => 'beforeend',
                    'hx-trigger' => 'submit',
                ]
            ],
        );
        return $this->render('colony/colony.html.twig', [
            'colony'            => $colony,
            'new_building_form' => $new_building_form,
            'new_ship_form'     => $new_ship_form,
        ]);
    }

    #[Route(
        '/colony/{colony_id}/building/{id}',
        name: 'building_update',
        methods: ["PUT"],
    )]
    public function update_building(
        int $colony_id,
        int $id,
        PlayerService $playerService,
        ColonyRepository $colonyRepository,
        EntityManagerInterface $doctrine,
    ): Response {
        $colony = $colonyRepository->find($colony_id);
        if ($colony->getPlayer() != $playerService->player) {
            throw new NotFoundHttpException();
        }
        $building = null;
        foreach ($colony->getBuildings() as $b) {
            if ($b->getId() == $id) {
                $building = $b;
            }
        }
        if (!$building) {
            throw new NotFoundHttpException();
        }
        $metal = $colony->getRessourceByType(0);
        if ($metal->getStock() >= $building->getNextLevelPrice()) {
            $metal->setStock($metal->getStock() - $building->getNextLevelPrice());
            $building->setLevel($building->getLevel() + 1);
            $doctrine->flush();
        }

        return $this->render('colony/update_building.html.twig', [
            'building' => $building,
            'resource' => $metal,
        ]);
    }

    #[Route(
        '/colony/{colony_id}/building/',
        name: 'building_create',
        methods: ["POST"],
    )]
    public function create_building(
        int $colony_id,
        PlayerService $playerService,
        ColonyRepository $colonyRepository,
        Request $request,
        EntityManagerInterface $doctrine,
    ) {
        $colony = $colonyRepository->find($colony_id);
        if ($colony->getPlayer() != $playerService->player) {
            throw new NotFoundHttpException();
        }

        $new_building = new ColonyBuilding();
        $new_building_form = $this->createForm(
            NewBuildingType::class,
            $new_building,
            ['colony_buildings' => $colony->getBuildings()],
        );
        $new_building_form->handleRequest($request);
        if ($new_building_form->isSubmitted() && $new_building_form->isValid()) {
            $new_building = $new_building_form->getData();

            $metal = $colony->getRessourceByType(0);
            if ($metal->getStock() >= $new_building->getNextLevelPrice()) {
                $metal->setStock($metal->getStock() - $new_building->getNextLevelPrice());
                $colony->addBuilding($new_building);
                $doctrine->persist($new_building);
                $doctrine->flush();
            }
        }
        return $this->render('colony/building.html.twig', [
            'building' => $new_building,
            'resource' => $metal,
        ]);
    }

    #[Route(
        '/colony/{colony_id}/ship',
        name: 'ship_create',
        methods: ['POST']
    )]
    public function create_ship(
        int $colony_id,
        Request $request,
        ColonyRepository $colonyRepository,
        PlayerService $playerService,
        EntityManagerInterface $doctrine,
    ): Response {
        $colony = $colonyRepository->find($colony_id);
        if ($colony->getPlayer() != $playerService->player) {
            throw new NotFoundHttpException();
        }

        $new_ship = new Ship();
        $new_ship_form = $this->createForm(NewShipType::class, $new_ship);
        $new_ship_form->handleRequest($request);
        if ($new_ship_form->isSubmitted() && $new_ship_form->isValid()) {
            # ToDo : check price
            $new_ship = $new_ship_form->getData();

            $new_fleet = new Fleet();
            $new_fleet->addShip($new_ship);
            $new_fleet->setName('New Fleet');
            $colony->getStellarObject()->addFleet($new_fleet);
            $playerService->player->addFleet($new_fleet);

            # ToDo : pay price

            $doctrine->persist($new_ship);
            $doctrine->persist($new_fleet);
            $doctrine->flush();
        }

        return $this->render('colony/fleet.html.twig', [
            'fleet'     => $new_fleet,
            'resources' => []
        ]);
    }

    #[Route(
        'colony/{id}/edit',
        name: 'colony_edit',
        methods: ['POST', 'GET'],
    )]
    public function rename(
        int $id,
        Request $request,
        ColonyRepository $colonyRepository,
        PlayerService $playerService,
        EntityManagerInterface $doctrine,
    ): Response {
        $colony = $colonyRepository->find($id);
        if (!$colony || $playerService->player != $colony->getPlayer()) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ColonyNameEdit::class, $colony);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $colony = $form->getData();
            $doctrine->flush();
            return $this->render(
                'colony/name.html.twig',
                ['colony' => $colony]
            );
        } else {
            return $this->render(
                'colony/name_edit.html.twig',
                ['form' => $form, 'colony' => $colony],
            );
        }
    }
}
