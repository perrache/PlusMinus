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

    public function Import1List(string $queryExtra = 'where i.use=0 order by i.valuedate desc, i.id'): array
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
       case when m.id is null then 'X' else 'O' end has,
       i.refer referAlias,
       m.id idM,
       k.name||' / '||t.name type,
       o.name||' / '||a.name account,
       m.comment
from import1 i
    left join minus m on i.value = -m.value and i.valuedate = m.dat
    left join type t on t.id = m.type_id
    left join kind k on k.id = t.kind_id
    left join transaction r on r.id = m.transaction_id
    left join account a on a.id = m.account_id
    left join organization o on o.id = a.organization_id
    left join currency c on c.id = a.currency_id
eof;

        $sql = $sqlMain . ' ' . $queryExtra;
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
