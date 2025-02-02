<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RootController extends AbstractController
{
    #[Route('/', name: 'app_root')]
    public function index(ClockInterface $clock): Response
    {
        $currClock = $clock->withTimeZone('Europe/Warsaw')->now()->format('d-m-Y H:i:s');
        return $this->render('root/index.html.twig', [
            'currClock' => $currClock,
        ]);
    }
}
