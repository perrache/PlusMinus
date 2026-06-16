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

    public function Import1List(string $queryExtra = 'and 1=1'): array
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
       i.value,
       to_char(i.value, :mask1) valueAlias,
       i.last lastAlias,
       i.use useAlias,
       i.refer referAlias
from import1 i
where 1=1
eof;

        $sql = $sqlMain . ' ' . $queryExtra;
        try {
            $res = $conn->executeQuery($sql, [
                'mask1' => $this->mask1,
                'mask2' => $this->mask2,
                'mask3' => $this->mask3,
            ]);
        } catch (Exception $e) {
            return [
                0 => [
                    'idalias' => -1,
                    'valuedatealias' => 'ERR',
                    'postingdatealias' => 'ERR',
                    'x' => 'ERR',
                    'typealias' => 'ERR',
                    'contractoralias' => '',
                    'titlealias' => '',
                    'categoryalias' => $e->getMessage(),
                    'value' => 0,
                    'valuealias' => 'ERR',
                    'lastalias' => 0,
                    'usealias' => 0,
                    'referalias' => '',
                ],
            ];
        }
        try {
            return $res->fetchAllAssociative();
        } catch (Exception $e) {
            return [
                0 => [
                    'idalias' => -1,
                    'valuedatealias' => 'ERR',
                    'postingdatealias' => 'ERR',
                    'x' => 'ERR',
                    'typealias' => 'ERR',
                    'contractoralias' => '',
                    'titlealias' => '',
                    'categoryalias' => $e->getMessage(),
                    'value' => 0,
                    'valuealias' => 'ERR',
                    'lastalias' => 0,
                    'usealias' => 0,
                    'referalias' => '',
                ],
            ];
        }
    }

    public function Import1Count(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = <<<'eof'
select count(*) cnt
from import1
eof;

        try {
            $res = $conn->executeQuery($sql);
        } catch (Exception $e) {
            return [
                0 => [
                    'cnt' => $e->getMessage(),
                ],
            ];
        }
        try {
            return $res->fetchAllAssociative();
        } catch (Exception $e) {
            return [
                0 => [
                    'cnt' => $e->getMessage(),
                ],
            ];
        }
    }
}
