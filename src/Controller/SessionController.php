<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\PlayerSession;
use App\Form\SessionType;
use App\Form\SessionPlayerLightType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/session')]
class SessionController extends AbstractController
{
    #[Route('/', name: 'app_session_index', methods: ['GET'])]
    public function index(SessionRepository $sessionRepository): Response
    {
        return $this->render('session/index.html.twig', [
            'sessions' => $sessionRepository->findAllWithFullSessionInfo(),
        ]);
    }

    #[Route('/new', name: 'app_session_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session_show',  array('id'=>$session->getId()), Response::HTTP_SEE_OTHER);
        }

        return $this->render('session/new.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_session_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id, Request $request, EntityManagerInterface $entityManager,SessionRepository $sessionRepository): Response
    {   $session = $sessionRepository->findSessionWithFullSessionInfo($id);
        $playerSession = new PlayerSession();
        $playerSession->setSession($session);
        $leftovers = $entityManager->getRepository('App\Entity\Player')->findPlayersNotAssignedToSession($session);
        $form = $this->createForm(SessionPlayerLightType::class, $playerSession, array('leftovers'=>$leftovers));
        
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'player_form' => $form
        ]);
    }

    #[Route('/{id}/edit', name: 'app_session_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Session $session, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('session/edit.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_session_delete', methods: ['POST'])]
    public function delete(Request $request, Session $session, EntityManagerInterface $entityManager): Response
    {

        $playerSession = new PlayerSession();
        $playerSession->setSession($session);
        $form = $this->createForm(SessionPlayerLightType::class, $playerSession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playerSession->setGameRole($entityManager->find('App\Entity\GameRole', $request->request->get('game_role_id')));
            $entityManager->persist($playerSession);
            $entityManager->flush();
            return $this->redirectToRoute('app_session_show', array('id'=>$session->getId()), Response::HTTP_SEE_OTHER);
        }elseif ($this->isCsrfTokenValid('delete'.$session->getId(), $request->request->get('_token'))) {
            $entityManager->remove($session);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
    }
}
