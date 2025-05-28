<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MainTableController extends MainSqlController
{
    #[Route('/sql/{tab}/{id}', name: 'route_sql', requirements: ['tab' => '\d+', 'id' => '\d+'], methods: ['GET'])]
    public function sql(Connection $conn, int $tab = 0, int $id = 0): Response
    {
        if ($tab === 1) {
            $sql1 = $this->sqlx01;
            $sql2 = $this->sqlx01_2;
            $title = '01 - Kinds';
        } elseif ($tab === 2) {
            $sql1 = $this->sqlx02;
            $sql2 = $this->sqlx02_2;
            $title = '02 - Types';
        } elseif ($tab === 3) {
            $sql1 = $this->sqlx03;
            $sql2 = $this->sqlx03_2;
            $title = '03 - Accounts';
        } elseif ($tab === 4) {
            $sql1 = $this->sqlx04;
            $sql2 = $this->sqlx04_2;
            $title = '04 - Transactions';
        } else {
            $sql1 = '';
            $sql2 = '';
            $title = '';
        }
        $mask1 = $this->getParameter('app.maska1');
        $mask2 = $this->getParameter('app.maska2');
        $mask3 = $this->getParameter('app.maska3');
        $tab = empty($sql2) ? -1 : $tab;
        try {
            $records1 = $conn->fetchAllAssociative($sql1, [
                'mask1' => $mask1,
                'mask2' => $mask2,
                'mask3' => $mask3,
            ]);
        } catch (Exception $e) {
            null;
        }
        $callArray = [
            'records1' => $records1,
            'title' => $title,
            'tab' => $tab,
            'id' => $id,
        ];
        if ($id !== 0) {
            try {
                $records2 = $conn->fetchAllAssociative($sql2, [
                    'mask1' => $mask1,
                    'mask2' => $mask2,
                    'mask3' => $mask3,
                    'id' => $id,
                ]);
            } catch (Exception $e) {
                null;
            }
            $callArray['records2'] = $records2;
        }
        return $this->render('main_table/table.html.twig', $callArray);
    }
}
