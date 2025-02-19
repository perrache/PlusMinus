<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainSqlController extends AbstractController
{
    public string $sqlx01 = <<<'eof'
select k.name skind,
       to_char(sum(m.value), :mask1) nsuma
from minus m
join type t on t.id = m.type_id
join kind k on k.id = t.kind_id
group by k.name
order by skind
eof;

    public string $sqlx02 = <<<'eof'
select k.name||' / '||t.name stype,
       t.id sid,
       to_char(sum(m.value), :mask1) nsuma
from minus m
join type t on t.id = m.type_id
join kind k on k.id = t.kind_id
group by k.name||' / '||t.name, t.id
order by stype
eof;

    public string $sqlx02_2 = <<<'eof'
select k.name||' / '||t.name stype,
       to_char(m.value, :mask1) nvalue,
       to_char(m.dat, :mask3) sdata,
       m.comment scomm
from minus m
join type t on t.id = m.type_id
join kind k on k.id = t.kind_id
where m.type_id = :id
order by m.dat desc, stype
eof;
}
