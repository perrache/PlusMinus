<?php

namespace App\Controller;

use App\Entity\Move;
use App\Form\MoveType;
use App\Repository\MoveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/move')]
final class MoveController extends AbstractController
{
    #[Route(name: 'app_move_index', methods: ['GET'])]
    public function index(MoveRepository $moveRepository): Response
    {
        return $this->render('move/index.html.twig', [
            'moves' => $moveRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_move_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $move = new Move();
        $form = $this->createForm(MoveType::class, $move);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($move);
            $entityManager->flush();

            return $this->redirectToRoute('app_move_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('move/new.html.twig', [
            'move' => $move,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_move_show', methods: ['GET'])]
    public function show(Move $move): Response
    {
        return $this->render('move/show.html.twig', [
            'move' => $move,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_move_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Move $move, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MoveType::class, $move);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_move_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('move/edit.html.twig', [
            'move' => $move,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_move_delete', methods: ['POST'])]
    public function delete(Request $request, Move $move, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$move->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($move);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_move_index', [], Response::HTTP_SEE_OTHER);
    }
}
