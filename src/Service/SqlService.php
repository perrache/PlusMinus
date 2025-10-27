<?php

namespace App\Service;

class SqlService
{
    public array $sqlArray = [
        1 => [
            'title' => 'Dictionary-Kinds-Types',
            'sql1' => '
select k.name skind, k.id sid
from kind k
order by skind',
            'sql2' => '
select t.name stype, t.id sid
from type t
where t.kind_id = :id
order by stype',
        ],
        4 => [
            'title' => 'Transactions(+minus)',
            'sql1' => '
select t.name stransaction, t.id sid, to_char(sum(m.value), :mask1) nsuma, count(*) sile
from minus m
    join transaction t on t.id = m.transaction_id
group by t.name, t.id
order by stransaction',
            'sql2' => '
select t.name stransaction, to_char(m.value, :mask1) nvalue, to_char(m.dat, :mask3) sdata, m.comment scomm
from minus m
    join transaction t on t.id = m.transaction_id
where t.id = :id
order by m.dat desc, stransaction, m.id desc',
        ],
        11 => [
            'title' => 'Sales-Kinds',
            'sql1' => '
select k.name skind, k.id sid, to_char(sum(m.value), :mask1) nsuma
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
group by k.name, k.id
order by skind',
            'sql2' => '
select k.name || \' / \' || t.name stype, to_char(m.value, :mask1) nvalue, to_char(m.dat, :mask3) sdata, m.comment scomm
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
where t.kind_id = :id
order by m.dat desc, stype, m.id desc',
        ],
    ];
}



