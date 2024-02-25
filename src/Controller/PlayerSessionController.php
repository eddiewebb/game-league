<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\PlayerSession;
use App\Entity\Session;
use App\Form\PlayerSessionType;
use App\Repository\PlayerSessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/player/session')]
class PlayerSessionController extends AbstractController
{
    #[Route('/', name: 'app_player_session_index', methods: ['GET'])]
    public function index(PlayerSessionRepository $playerSessionRepository): Response
    {
        return $this->render('player_session/index.html.twig', [
            'player_sessions' => $playerSessionRepository->findAll(),
        ]);
    }

    #[Route('/new/{session_id}', name: 'app_player_session_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $session_id=1): Response
    {
        $playerSession = new PlayerSession();
        $playerSession->setSession($entityManager->find('App\Entity\Session', $session_id));
        $form = $this->createForm(PlayerSessionType::class, $playerSession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($playerSession);
            $entityManager->flush();

            return $this->redirectToRoute('app_player_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('player_session/new.html.twig', [
            'player_session' => $playerSession,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_player_session_show', methods: ['GET'])]
    public function show(PlayerSession $playerSession): Response
    {
        return $this->render('player_session/show.html.twig', [
            'player_session' => $playerSession,
        ]);
    }

    #[Route('/{id}/edit/{return}', name: 'app_player_session_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PlayerSession $playerSession, EntityManagerInterface $entityManager, string $return='app_player_session_index'): Response
    {
        $form = $this->createForm(PlayerSessionType::class, $playerSession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $option = [];
            if ($return == 'app_session_show'){
                $option = array('id'=>$playerSession->getSession()->getId());
            }
            return $this->redirectToRoute($return, $option, Response::HTTP_SEE_OTHER);
        }

        return $this->render('player_session/edit.html.twig', [
            'player_session' => $playerSession,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_player_session_delete', methods: ['POST'])]
    public function delete(Request $request, PlayerSession $playerSession, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$playerSession->getId(), $request->request->get('_token'))) {
            $entityManager->remove($playerSession);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_player_session_index', [], Response::HTTP_SEE_OTHER);
    }
}
