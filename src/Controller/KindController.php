<?php

namespace App\Controller;

use App\Entity\Kind;
use App\Form\KindType;
use App\Repository\KindRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/kind')]
final class KindController extends AbstractController
{
    #[Route(name: 'app_kind_index', methods: ['GET'])]
    public function index(KindRepository $kindRepository): Response
    {
        return $this->render('kind/index.html.twig', [
            'kinds' => $kindRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_kind_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $kind = new Kind();
        $form = $this->createForm(KindType::class, $kind);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($kind);
            $entityManager->flush();

            return $this->redirectToRoute('app_kind_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('kind/new.html.twig', [
            'kind' => $kind,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_kind_show', methods: ['GET'])]
    public function show(Kind $kind): Response
    {
        return $this->render('kind/show.html.twig', [
            'kind' => $kind,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_kind_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Kind $kind, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(KindType::class, $kind);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_kind_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('kind/edit.html.twig', [
            'kind' => $kind,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_kind_delete', methods: ['POST'])]
    public function delete(Request $request, Kind $kind, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$kind->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($kind);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_kind_index', [], Response::HTTP_SEE_OTHER);
    }
}
