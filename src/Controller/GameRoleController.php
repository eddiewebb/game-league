<?php

namespace App\Controller;

use App\Entity\GameRole;
use App\Form\GameRoleType;
use App\Repository\GameRoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game/role')]
class GameRoleController extends AbstractController
{
    #[Route('/', name: 'app_game_role_index', methods: ['GET'], priority: 0)]
    public function index(GameRoleRepository $gameRoleRepository): Response
    {
        return $this->render('game_role/index.html.twig', [
            'game_roles' => $gameRoleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_game_role_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gameRole = new GameRole();
        $form = $this->createForm(GameRoleType::class, $gameRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gameRole);
            $entityManager->flush();

            return $this->redirectToRoute('app_game_role_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game_role/new.html.twig', [
            'game_role' => $gameRole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_game_role_show', methods: ['GET'])]
    public function show(GameRole $gameRole): Response
    {
        return $this->render('game_role/show.html.twig', [
            'game_role' => $gameRole,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_game_role_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GameRole $gameRole, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GameRoleType::class, $gameRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_game_role_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game_role/edit.html.twig', [
            'game_role' => $gameRole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_game_role_delete', methods: ['POST'])]
    public function delete(Request $request, GameRole $gameRole, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gameRole->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gameRole);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_game_role_index', [], Response::HTTP_SEE_OTHER);
    }
}
