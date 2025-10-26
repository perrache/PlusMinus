<?php

namespace App\Controller;

use App\Service\SqlService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TableController extends AbstractController
{
    #[Route('/sql/{tab}/{id}', name: 'route_sql', requirements: ['tab' => '\d+', 'id' => '\d+'], methods: ['GET'])]
    public function sql(Connection $conn, SqlService $sqlService, int $tab = 0, int $id = 0): Response
    {
        if ($tab <= 0) return $this->redirectToRoute('route_root', [], Response::HTTP_SEE_OTHER);
        $sql1 = $sqlService->sqlArray[$tab]['sql1'];
        $sql2 = $sqlService->sqlArray[$tab]['sql2'];
        $title = $sqlService->sqlArray[$tab]['title'];
        $mask1 = $this->getParameter('app.maska1');
        $mask2 = $this->getParameter('app.maska2');
        $mask3 = $this->getParameter('app.maska3');
//        $tab = empty($sql2) ? -1 : $tab;
        try {
            $records1 = $conn->fetchAllAssociative($sql1, [
                'mask1' => $mask1,
                'mask2' => $mask2,
                'mask3' => $mask3,
            ]);
        } catch (Exception $e) {
            return $this->redirectToRoute('route_root', [], Response::HTTP_SEE_OTHER);
        }
        $callArray = [
            'records1' => $records1,
            'title' => sprintf('%02u - ', $tab) . $title,
            'tab' => empty($sql2) ? -1 : $tab,
            'id' => $id,
        ];
        if ($id > 0) {
            try {
                $records2 = $conn->fetchAllAssociative($sql2, [
                    'mask1' => $mask1,
                    'mask2' => $mask2,
                    'mask3' => $mask3,
                    'id' => $id,
                ]);
            } catch (Exception $e) {
                return $this->redirectToRoute('route_root', [], Response::HTTP_SEE_OTHER);
            }
            $callArray['records2'] = $records2;
        }
        return $this->render('table/index.html.twig', $callArray);
    }
}
