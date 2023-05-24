<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use App\Form\PlayerType;
use App\Form\PlayerTransferType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    /**
     * @Route("/player/add", name="player_add")
     */
    public function add(Request $request): Response
    {
        $player = new Player();

        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($player);
            $entityManager->flush();

            return $this->redirectToRoute('team_index');
        }

        return $this->render('player/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/player/transfer", name="player_transfer")
     */
    public function transfer(Request $request): Response
    {
        $form = $this->createForm(PlayerTransferType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playerId = $form->get('player')->getData();
            $teamToId = $form->get('teamTo')->getData();
            $transferAmount = $form->get('transferAmount')->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $player = $entityManager->getRepository(Player::class)->find($playerId);
            $teamTo = $entityManager->getRepository(Team::class)->find($teamToId);

            if (!$player || !$teamTo) {
                throw $this->createNotFoundException('No player/team found for id '.$playerId.'/'.$teamToId);
            }

            // check if the selling team has enough money
            if ($player->getTeam()->getMoneyBalance() < $transferAmount) {
                throw $this->createNotFoundException('Not enough money for this transfer');
            }

            // Update the money balance of the selling team and buying team
            $player->getTeam()->setMoneyBalance($player->getTeam()->getMoneyBalance() - $transferAmount);
            $teamTo->setMoneyBalance($teamTo->getMoneyBalance() + $transferAmount);

            // Update the player's team
            $player->setTeam($teamTo);

            $entityManager->flush();

            return $this->redirectToRoute('team_index');
        }

        return $this->render('player/transfer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
