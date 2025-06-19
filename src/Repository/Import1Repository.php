<?php

namespace App\Repository;

use App\Entity\Import1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Import1>
 */
class Import1Repository extends ServiceEntityRepository
{
    private string $mask1;
    private string $mask2;
    private string $mask3;

    public function __construct(ManagerRegistry $registry,
                                string          $mask1,
                                string          $mask2,
                                string          $mask3)
    {
        parent::__construct($registry, Import1::class);
        $this->mask1 = $mask1;
        $this->mask2 = $mask2;
        $this->mask3 = $mask3;
    }

    public function Import1List(int $sqlWhere = 1, int $sqlOrder = 1): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sqlMain = <<<'eof'
select i.id idAlias,
       to_char(i.postingdate, :mask3) postingdateAlias,
       to_char(i.valuedate, :mask3) valuedateAlias,
       case when i.postingdate <> i.valuedate then 'X' else '' end x,
       i.type typeAlias,
       i.contractor contractorAlias,
       i.title titleAlias,
       i.category categoryAlias,
       to_char(i.value, :mask1) valueAlias,
       i.last lastAlias,
       i.use useAlias
from import1 i
eof;

        $sqlWhereTab = [
            1 => 'where true',
            2 => 'where i.last = 1',
            3 => 'where i.use = 1',
            4 => 'where i.last = 1 and i.use = 1',
        ];
        $sqlOrderTab = [
            1 => 'order by i.valuedate desc, i.id',
            2 => 'order by i.postingdate desc, i.id',
        ];

        $sql = $sqlMain . ' ' . $sqlWhereTab[$sqlWhere] . ' ' . $sqlOrderTab[$sqlOrder];
        try {
            $res = $conn->executeQuery($sql, [
                'mask1' => $this->mask1,
                'mask2' => $this->mask2,
                'mask3' => $this->mask3,
            ]);
        } catch (Exception $e) {
            return [];
        }
        try {
            return $res->fetchAllAssociative();
        } catch (Exception $e) {
            return [];
        }
    }
}
