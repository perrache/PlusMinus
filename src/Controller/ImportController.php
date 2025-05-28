<?php

namespace App\Controller;

use App\Form\PlikDoTabeli1Type;
use App\Service\StringConverter;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/imp')]
final class ImportController extends AbstractController
{
    #[Route('/plikdotabeli1', name: 'route_imp_plikdotabeli1')]
    public function plikdotabeli1(Request                $request,
                                  StringConverter        $stringConverter,
                                  ClockInterface         $clock,
                                  EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlikDoTabeli1Type::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rowCount = 0;
            $rowCount2 = 0;
            $fileArray = [];
            $logs = [];
            $file = $form->get('file')->getData();
            $logs[] = $clock->withTimeZone('Europe/Warsaw')->now()->format('d-m-Y H:i:s');
            if (($handle = fopen("import/$file", "r")) !== FALSE) {
                while (($row = fgetcsv($handle, null, ";")) !== FALSE) {
                    if ($rowCount > 0) {
                        $fileArray[] = [
                            $row[0],
                            $row[1],
                            $row[2],
                            $row[4],
                            $row[5],
                            $row[6],
                            $row[7],
                            $row[9],
                            $row[10],
                            $row[11],
//                        $stringConverter->cp1250utf8($row[2]),
                        ];
                    }
                    $rowCount2 += 1;
                    $rowCount += 1;
                }
                fclose($handle);
                $logs[] = 'nazwa pliku: ' . $file;
                $logs[] = 'ilość wierszy ogółem: ' . $rowCount . ' w tym aktywnych: ' . $rowCount2;
//                foreach ($fileArray as $fileRow) {
//                    $importminus = new Importminus();
//                    $importminus->setAccount($account);
//                    $importminus->setDat($fileRow[0]);
//                    $importminus->setContractor($fileRow[1]);
//                    $importminus->setBill($fileRow[2]);
//                    $importminus->setBank($fileRow[3]);
//                    $importminus->setTitle($fileRow[4]);
//                    $importminus->setDetails($fileRow[5]);
//                    $importminus->setTransaction($fileRow[6]);
//                    $importminus->setValue($fileRow[7]);
//                    $importminus->setDatin(new \DateTime('now'));
//                    $entityManager->persist($importminus);
//                    $entityManager->flush();
//                }
            } else $logs[] = 'File error: ' . $file;
            return $this->render('import/log.html.twig', ['logs' => $logs]);
        }
        return $this->render('import/plikdotabeli.html.twig', ['form' => $form]);
    }
}
