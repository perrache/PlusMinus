<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RootController extends AbstractController
{
    #[Route('/{mnu}', name: 'route_root', requirements: ['mnu' => '\d+'], methods: ['GET'])]
    public function index(ClockInterface $clock, int $mnu = 0): Response
    {
        $currClock = $clock->withTimeZone('Europe/Warsaw')->now()->format('d-m-Y H:i:s');
        return $this->render('root/index.html.twig', [
            'currClock' => $currClock,
            'mnu' => $mnu,
        ]);
    }

    #[Route('/png', name: 'route_root_png', methods: ['GET'])]
    public function indexpng(ClockInterface $clock): Response
    {
        $currClock = $clock->withTimeZone('Europe/Warsaw')->now()->format('d-m-Y H:i:s');
        return $this->render('root/indexpng.html.twig', [
            'currClock' => $currClock,
        ]);
    }
}
