<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Entity\Game;
use App\Entity\GameRole;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GameRoleController extends AbstractController
{

    


    #[Route('/game/role', name: 'app_game_role', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('game_role/index.html.twig', [
            'controller_name' => 'GameRoleController',
        ]);
    }


    #[Route('/game', name: 'create_game',methods: ['POST'])]
    public function creategame(EntityManagerInterface $entityManager): Response
    {
        $game = new Game();
        $game->setName('Root');
        // tell Doctrine you want to (eventually) save the game (no queries yet)
       // $entityManager->persist($game);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new game with id '.$game->getId());
    }
    #[Route('/game/role', name: 'create_gameRole',methods: ['POST'])]
    public function creategameRole(LoggerInterface $logger,EntityManagerInterface $entityManager): Response
    {
        $logger->info("Creating");
        $game = $entityManager->getRepository(Game::class)->find(1);
        $gameRole = new GameRole();
        $gameRole->setName('Vagabond (Racoon)');

        $gameRole->setGame($game);
        // tell Doctrine you want to (eventually) save the gameRole (no queries yet)
        //$entityManager->persist($gameRole);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new gameRole with id '.$gameRole->getId());
    }

}
