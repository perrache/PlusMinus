<?php

namespace App\Controller;

use App\Entity\Plus;
use App\Form\PlusType;
use App\Repository\PlusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/plus')]
final class PlusController extends AbstractController
{
    #[Route(name: 'app_plus_index', methods: ['GET'])]
    public function index(PlusRepository $plusRepository): Response
    {
        return $this->render('plus/index.html.twig', [
            'pluses' => $plusRepository->findBy([], ['dat' => 'DESC', 'id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_plus_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $plu = new Plus();
        $form = $this->createForm(PlusType::class, $plu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plu);
            $entityManager->flush();

            return $this->redirectToRoute('app_plus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plus/new.html.twig', [
            'plu' => $plu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plus_show', methods: ['GET'])]
    public function show(Plus $plu): Response
    {
        return $this->render('plus/show.html.twig', [
            'plu' => $plu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_plus_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plus $plu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlusType::class, $plu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_plus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plus/edit.html.twig', [
            'plu' => $plu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plus_delete', methods: ['POST'])]
    public function delete(Request $request, Plus $plu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $plu->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($plu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_plus_index', [], Response::HTTP_SEE_OTHER);
    }
}
