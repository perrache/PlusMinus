<?php

namespace App\Controller;

use App\Entity\Import1;
use App\Form\PlikDoTabeli1Type;
use App\Repository\Import1Repository;
use App\Service\StringConverter;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
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
                                  EntityManagerInterface $entityManager,
                                  Connection             $conn,
                                  Import1Repository      $import1Repository): Response
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

                $res = $conn->prepare('update import1 set last = 0')->executeStatement();

                foreach ($fileArray as $fileRow) {
                    if (is_null($import1Repository->findOneBy(['transaction' => $fileRow[7]]))) {
                        $import1 = new Import1();
                        $import1->setPostingdate(new \DateTime($fileRow[0]));
                        $import1->setValuedate(new \DateTime($fileRow[1]));
                        $import1->setContractor($fileRow[2]);
                        $import1->setBillsource($fileRow[3]);
                        $import1->setBilldestination($fileRow[4]);
                        $import1->setTitle($fileRow[5]);
                        $import1->setValue((int)((float)$fileRow[6] * 100));
                        $import1->setTransaction($fileRow[7]);
                        $import1->setType($fileRow[8]);
                        $import1->setCategory($fileRow[9]);
                        $import1->setLast(1);
                        $import1->setUse(0);
//
                        $entityManager->persist($import1);
                        $entityManager->flush();
                    } else {
                        $rowCount2 += 1;
                    }
                }
                $logs[] = 'nazwa pliku: ' . $file;
                $logs[] = 'ilość wierszy ogółem: ' . $rowCount - 1 . ' w tym NIEaktywnych: ' . $rowCount2;
            } else $logs[] = 'File error: ' . $file;
            return $this->render('import/log.html.twig', ['logs' => $logs]);
        }
        return $this->render('import/plikdotabeli.html.twig', ['form' => $form]);
    }

    #[Route('/plikdotabeli2', name: 'route_imp_plikdotabeli2')]
    public function plikdotabeli2(Connection $conn): Response
    {
        return $this->render('import/plikdotabeli2.html.twig');
    }
}


// UserRepository.php
//$entityManager = $this->getEntityManager();
//$conn = $entityManager->getConnection();
//return $conn->executeQuery("SELECT * FROM user")->fetchAll();

//$db = Doctrine_Manager::getInstance()->getCurrentConnection();
//$query = $db->prepare("SELECT `someField` FROM `someTable` WHERE `field` = :value");
//$query->execute(array('value' => 'someValue'));

//$conn = $this->getEntityManager()->getConnection();
//        $sql = 'SELECT * FROM fortune_cookie';
//        $stmt = $conn->prepare($sql);
//        $result = $stmt->executeQuery();
//        dd($result->fetchAllAssociative());

//$logs = [];
//$logs[] = 'dupa';
//$sql = "update import1 set contractor = 'dupa' where id = 430";
//try {
//    $res = $conn->prepare($sql)->executeStatement();
//} catch (Exception $e) {
//    $logs[] = $e->getMessage();
//}
//return $this->render('import/log.html.twig', ['logs' => $logs]);

