<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainTableController extends MainSqlController
{
    #[Route('/sql/{tab}/{id}', name: 'route_sql', requirements: ['tab' => '\d+', 'id' => '\d+'], methods: ['GET'])]
    public function sql(Connection $conn, int $tab = 0, int $id = 0): Response
    {
        if ($tab === 1) {
            $sql1 = $this->sqlx01;
            $sql2 = '';
            $title = '01 - Kinds';
        } elseif ($tab === 2) {
            $sql1 = $this->sqlx02;
            $sql2 = $this->sqlx02_2;
            $title = '02 - Kinds, Types';
        } elseif ($tab === 3) {
            $sql1 = '';
            $sql2 = '';
            $title = '';
        } else {
            $sql1 = '';
            $sql2 = '';
            $title = '';
        }
        $mask1 = $this->getParameter('app.maska1');
        $mask2 = $this->getParameter('app.maska2');
        $mask3 = $this->getParameter('app.maska3');
        $tab = empty($sql2) ? -1 : $tab;
        $records1 = $conn->fetchAllAssociative($sql1, [
            'mask1' => $mask1,
            'mask2' => $mask2,
            'mask3' => $mask3,
        ]);
        $callArray = [
            'records1' => $records1,
            'title' => $title,
            'tab' => $tab,
            'id' => $id,
        ];
        if ($id !== 0) {
            $records2 = $conn->fetchAllAssociative($sql2, [
                'mask1' => $mask1,
                'mask2' => $mask2,
                'mask3' => $mask3,
                'id' => $id,
            ]);
            $callArray['records2'] = $records2;
        }
        return $this->render('main_table/table.html.twig', $callArray);
    }
}
