<?php

namespace App\Controller;

use App\Entity\Saldo;
use App\Form\SaldoType;
use App\Repository\SaldoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/saldo')]
final class SaldoController extends AbstractController
{
    #[Route(name: 'app_saldo_index', methods: ['GET'])]
    public function index(SaldoRepository $saldoRepository): Response
    {
        return $this->render('saldo/index.html.twig', [
            'saldos' => $saldoRepository->findBy([], ['dat' => 'DESC', 'id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_saldo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $saldo = new Saldo();
        $saldo->setDat(new \DateTime('now'));
        $form = $this->createForm(SaldoType::class, $saldo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($saldo);
            $entityManager->flush();

            return $this->redirectToRoute('app_saldo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('saldo/new.html.twig', [
            'saldo' => $saldo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_saldo_show', methods: ['GET'])]
    public function show(Saldo $saldo): Response
    {
        return $this->render('saldo/show.html.twig', [
            'saldo' => $saldo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_saldo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Saldo $saldo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SaldoType::class, $saldo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_saldo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('saldo/edit.html.twig', [
            'saldo' => $saldo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_saldo_delete', methods: ['POST'])]
    public function delete(Request $request, Saldo $saldo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$saldo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($saldo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_saldo_index', [], Response::HTTP_SEE_OTHER);
    }
}
