<?php

namespace App\Controller;

use App\Entity\Import1;
use App\Form\Import1Type;
use App\Form\PlikDoTabeli1Type;
use App\Repository\Import1Repository;
use App\Service\StringConverter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/imp')]
final class ImportController extends AbstractController
{
    #[Route('/plikdotabeli1', name: 'route_imp_plikdotabeli1', methods: ['GET', 'POST'])]
    public function plikdotabeli1(Request                $request,
                                  StringConverter        $stringConverter,
                                  ClockInterface         $clock,
                                  EntityManagerInterface $entityManager,
                                  Connection             $conn,
                                  Import1Repository      $import1Repository): Response
    {
        $form = $this->createForm(PlikDoTabeli1Type::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//
            $logs = [];
            $logs[] = $clock->withTimeZone('Europe/Warsaw')->now()->format('d-m-Y H:i:s');
            try {
                $result = $conn->fetchAllAssociative('select count(*) c from account where import = 1');
            } catch (Exception $e) {
                $logs[] = 'Select count error';
            }
            if ($result[0]['c'] == 1) {
                $file = $form->get('file')->getData();
                $fileArray = [];
                $rowCount = 0;
                $rowCount2 = 0;
                if (($handle = fopen("import/$file", "r")) !== FALSE) {
                    while (($row = fgetcsv($handle, null, ";", "\"", "")) !== FALSE) {
                        if ($rowCount > 0) {
                            $fileArray[] = [
                                $row[0],
                                $row[1],
                                $row[2],
                                $stringConverter->firstLast($row[4]),
                                $stringConverter->firstLast($row[5]),
                                $row[6],
                                $row[7],
                                $stringConverter->firstLast($row[9]),
                                $row[10],
                                $row[11],
                            ];
                        }
                        $rowCount += 1;
                    }
                    fclose($handle);

                    try {
                        $res = $conn->prepare('update import1 set last = 0')->executeStatement();
                    } catch (Exception $e) {
                        $logs[] = 'Update error';
                    }

                    foreach ($fileArray as $fileRow) {
                        if (is_null($import1Repository->findOneBy(['refer' => $fileRow[7]]))) {
                            $import1 = new Import1();
                            $import1->setPostingdate(new \DateTime($fileRow[0]));
                            $import1->setValuedate(new \DateTime($fileRow[1]));
                            $import1->setContractor($fileRow[2]);
                            $import1->setBillsource($fileRow[3]);
                            $import1->setBilldestination($fileRow[4]);
                            $import1->setTitle($fileRow[5]);
                            $import1->setValue((int)(((float)strtr(str_replace(' ', '', $fileRow[6]), ',', '.')) * 100));
                            $import1->setRefer($fileRow[7]);
                            $import1->setType($fileRow[8]);
                            $import1->setCategory($fileRow[9]);
                            $import1->setLast(1);
                            $import1->setUse(0);

                            $entityManager->persist($import1);
                            $entityManager->flush();
                        } else {
                            $rowCount2 += 1;
                            $logs[] = 'JUŻ ISTNIEJĄCY: ' . $fileRow[7];
                        }
                    }
                    $logs[] = 'nazwa pliku: ' . $file;
                    $logs[] = 'ilość wierszy ogółem: ' . $rowCount - 1 . ' w tym JUŻ ISTNIEJĄCYCH: ' . $rowCount2;
                } else $logs[] = 'File open error: ' . $file;
            } else $logs[] = 'Error AccountImport';
            return $this->render('log/log.html.twig', ['logs' => $logs]);
//
        }
        return $this->render('import/plikdotabeli.html.twig', ['form' => $form]);
    }

    #[Route('/import1', name: 'route_imp_import1', methods: ['GET', 'POST'])]
    public function import1(Import1Repository $import1Repository,
                            Request           $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('sessionQueryExtra')) $session->set('sessionQueryExtra', 'where 1=1');

        $form = $this->createForm(Import1Type::class, null, [
            'initialValue' => $session->get('sessionQueryExtra', 'where 1=1')
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//
            $session->set('sessionQueryExtra', $form->get('queryExtra')->getData());
            return $this->render('import/import1.html.twig', [
                'records1' => $import1Repository->Import1List($session->get('sessionQueryExtra', 'where 1=1')),
                'sessionQueryExtra' => $session->get('sessionQueryExtra', 'where 1=1'),
            ]);
//
        }
        return $this->render('import/import.html.twig', ['form' => $form]);
    }

    #[Route('/import1Use/{iid}', name: 'route_imp_import1Use', methods: ['GET'])]
    public function import1Use(Connection $conn, int $iid = 0): Response
    {
        try {
            $res = $conn->prepare("update import1 set use = 1 where id = $iid")->executeStatement();
        } catch (Exception $e) {
            return $this->redirectToRoute('route_root', [], Response::HTTP_SEE_OTHER);
        }
        return $this->redirectToRoute('route_imp_import1', [], Response::HTTP_SEE_OTHER);
    }
}
