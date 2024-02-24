<?php

namespace App\Controller;


use Psr\Log\LoggerInterface;
use App\Entity\Player;
use App\Entity\Session;
use App\Entity\PlayerSession;
use App\Entity\GameRole;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session',methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }

    #[Route('/session/{id}')]
    public function show(Session $session): Response
    {
        // use the Game Session!
        // ...
    }

    #[Route('/session', name: 'create_session',methods: ['POST'])]
    public function createsession(EntityManagerInterface $entityManager): Response
    {
        $session = new Session();
        $session->setDate(new \DateTime());

        // tell Doctrine you want to (eventually) save the session (no queries yet)
        $entityManager->persist($session);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new session with id '.$session->getId());
    }

    #[Route('/players', name: 'create_player',methods: ['POST'])]
    public function createplayer(LoggerInterface $logger,EntityManagerInterface $entityManager): Response
    {
        $players = ['Eddie','Eli','Caleb','Rubin'];
        foreach ($players as $key => $name) {
        
            $player = new player();
            $player->setName($name);

            // tell Doctrine you want to (eventually) save the player (no queries yet)
            //$entityManager->persist($player);
            $logger->info('Saved new player with id '.$player->getId());
        }
            // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new players');
    }
}
