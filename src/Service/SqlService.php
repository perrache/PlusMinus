<?php

namespace App\Service;

class SqlService
{
    public array $sqlArray = [
        1 => [
            'title' => 'Dictionary-Kind-Type',
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
        2 => [
            'title' => 'Dictionary-Organization-Account',
            'sql1' => '
select o.name sorganization, o.id sid
from organization o
order by sorganization',
            'sql2' => '
select a.name saccount, c.code scode, a.id sid
from account a
    join currency c on c.id = a.currency_id
where a.organization_id = :id
order by saccount',
        ],
        11 => [
            'title' => 'Turnover-Kinds',
            'sql1' => '
select k.name skind, k.id sid, to_char(sum(m.value), :mask1) nsuma, count(*) nile
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
group by k.name, k.id
order by skind',
            'sql2' => '
select k.name||\' / \'||t.name stype, to_char(m.value, :mask1) nvalue, to_char(m.dat, :mask3) sdata, m.comment scomm
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
where t.kind_id = :id
order by m.dat desc, stype, m.id desc',
        ],
        12 => [
            'title' => 'Turnover-Types',
            'sql1' => '
select k.name||\' / \'||t.name stype, t.id sid, to_char(sum(m.value), :mask1) nsuma, count(*) nile
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
group by k.name||\' / \'||t.name, t.id
order by stype',
            'sql2' => '
select k.name||\' / \'||t.name stype, to_char(m.value, :mask1) nvalue, to_char(m.dat, :mask3) sdata, m.comment scomm
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
where m.type_id = :id
order by m.dat desc, stype, m.id desc',
        ],
        13 => [
            'title' => 'Turnover-Accounts',
            'sql1' => '
select tab.oname||\' / \'||tab.aname saccount,
       tab.id sid,
       case c.code when \'PLN\' then \'\' else c.code end swal,
       to_char(sum(tab.bo), :mask1) nbo,
       to_char(sum(tab.val), :mask1) nsaldo,
       to_char(sum(tab.val+tab.lt), :mask1) ndost
from
    (select o.name oname, a.name aname, a.id, a.bo, a.bo val, a.lt, a.currency_id
     from account a
         join organization o on o.id = a.organization_id
     union all
     select o.name oname, a.name aname, a.id, 0 bo, -m.value val, 0 lt, a.currency_id
     from minus m
         join account a on a.id = m.account_id
         join organization o on o.id = a.organization_id
     union all
     select o.name oname, a.name aname, a.id, 0 bo, p.value val, 0 lt, a.currency_id
     from plus p
         join account a on a.id = p.account_id
         join organization o on o.id = a.organization_id
     union all
     select o.name oname, a.name aname, a.id, 0 bo, -pm.value val, 0 lt, a.currency_id
     from move pm
         join account a on a.id = pm.accminus_id
         join organization o on o.id = a.organization_id
     union all
     select o.name oname, a.name aname, a.id, 0 bo, pm.value val, 0 lt, a.currency_id
     from move pm
         join account a on a.id = pm.accplus_id
         join organization o on o.id = a.organization_id
     ) tab
        join currency c on c.id = tab.currency_id
group by tab.oname||\' / \'||tab.aname, tab.id, c.code
order by saccount',
            'sql2' => '
select k.name||\' / \'||t.name stype, to_char(-m.value, :mask1) nvalue, m.dat xdat, to_char(m.dat, :mask3) sdata, m.comment scomm
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
where m.account_id = :id
union all
select \'+\' stype, to_char(p.value, :mask1) nvalue, p.dat xdat, to_char(p.dat, :mask3) sdata, p.comment scomm
from plus p
where p.account_id = :id
union all
select \'+-\' stype, to_char(pm.value, :mask1) nvalue, pm.dat xdat, to_char(pm.dat, :mask3) sdata, pm.comment scomm
from move pm
where pm.accplus_id = :id
union all
select \'+-\' stype, to_char(-pm.value, :mask1) nvalue, pm.dat xdat, to_char(pm.dat, :mask3) sdata, pm.comment scomm
from move pm
where pm.accminus_id = :id
order by xdat desc, stype',
        ],
        14 => [
            'title' => 'Turnover-Transactions',
            'sql1' => '
select t.name stransaction, t.id sid, to_char(sum(m.value), :mask1) nsuma, count(*) nile
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
        21 => [
            'title' => 'Minus-All',
            'sql1' => '
select k.name||\' / \'||t.name stype,
       r.name stransaction,
       o.name||\' / \'||a.name saccount,
       case c.code when \'PLN\' then \'\' else c.code end swal,
       to_char(m.value, :mask1) nvalue,
       to_char(m.dat, :mask3) sdata,
       m.comment scomment
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join transaction r on r.id = m.transaction_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
order by m.dat desc, m.id desc',
            'sql2' => '',
        ],
        22 => [
            'title' => 'Move-All',
            'sql1' => '
select oplus.name||\' / \'||aplus.name saccountplus,
       case cplus.code when \'PLN\' then \'\' else cplus.code end swalplus,
       ominus.name||\' / \'||aminus.name saccountminus,
       case cminus.code when \'PLN\' then \'\' else cminus.code end swalminus,
       to_char(m.value, :mask1) nvalue,
       to_char(m.dat, :mask3) sdata,
       m.comment scomment
from move m
    join account aplus on aplus.id = m.accplus_id
    join organization oplus on oplus.id = aplus.organization_id
    join currency cplus on cplus.id = aplus.currency_id
    join account aminus on aminus.id = m.accminus_id
    join organization ominus on ominus.id = aminus.organization_id
    join currency cminus on cminus.id = aminus.currency_id
order by m.dat desc, m.id desc',
            'sql2' => '',
        ],
        23 => [
            'title' => 'Minus-Comments-Sum',
            'sql1' => '
select m.comment scomment, to_char(sum(m.value), :mask1) nsuma, count(*) nile
from minus m
group by m.comment
order by scomment',
            'sql2' => '',
        ],
        24 => [
            'title' => 'Minus-Comments-Order',
            'sql1' => '
select k.name||\' / \'||t.name stype,
       r.name stransaction,
       o.name||\' / \'||a.name saccount,
       case c.code when \'PLN\' then \'\' else c.code end swal,
       to_char(m.value, :mask1) nvalue,
       to_char(m.dat, :mask3) sdata,
       m.comment scomment
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join transaction r on r.id = m.transaction_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
order by scomment, m.dat desc, m.id desc',
            'sql2' => '',
        ],
        31 => [
            'title' => 'Bilans',
            'sql1' => '
select t2.mies smies,
       to_char(sum(t2.valplus), :mask1) nplus,
       to_char(sum(t2.valminus), :mask1) nminus,
       to_char(sum(t2.valplus - t2.valminus), :mask1) nsaldo
from (
select case when t1.sign = \'plus\' then t1.val else 0 end valplus,
       case when t1.sign = \'minus\' then t1.val else 0 end valminus,
       t1.data mies
from (
select \'minus\' sign, value val, to_char(dat, \'YYYY/MM\') data from minus
union all
select \'plus\' sign, value val, to_char(dat, \'YYYY/MM\') data from plus
union all
select \'plus\' sign, sum(bo) val, \'0000/00\' data from account
) t1
) t2
group by grouping sets ((t2.mies), ())
order by t2.mies
',
            'sql2' => '',
        ],
    ];
}
