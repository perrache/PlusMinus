<?php

namespace App\Controller;

use App\Entity\Minus;
use App\Form\MinusType;
use App\Repository\MinusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/minus')]
final class MinusController extends AbstractController
{
    #[Route(name: 'app_minus_index', methods: ['GET'])]
    public function index(MinusRepository $minusRepository): Response
    {
        return $this->render('minus/index.html.twig', [
            'minuses' => $minusRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_minus_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $minu = new Minus();
        $form = $this->createForm(MinusType::class, $minu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($minu);
            $entityManager->flush();

            return $this->redirectToRoute('app_minus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('minus/new.html.twig', [
            'minu' => $minu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_minus_show', methods: ['GET'])]
    public function show(Minus $minu): Response
    {
        return $this->render('minus/show.html.twig', [
            'minu' => $minu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_minus_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Minus $minu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MinusType::class, $minu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_minus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('minus/edit.html.twig', [
            'minu' => $minu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_minus_delete', methods: ['POST'])]
    public function delete(Request $request, Minus $minu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$minu->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($minu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_minus_index', [], Response::HTTP_SEE_OTHER);
    }
}
