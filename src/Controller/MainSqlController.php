<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainSqlController extends AbstractController
{
    public string $sqlx01 = <<<'eof'
select k.name skind,
       k.id sid,
       to_char(sum(m.value), :mask1) nsuma
from minus m
join type t on t.id = m.type_id
join kind k on k.id = t.kind_id
group by k.name, k.id
order by skind
eof;

    public string $sqlx01_2 = <<<'eof'
select k.name||' / '||t.name stype,
       to_char(m.value, :mask1) nvalue,
       to_char(m.dat, :mask3) sdata,
       m.comment scomm
from minus m
join type t on t.id = m.type_id
join kind k on k.id = t.kind_id
where t.kind_id = :id
order by m.dat desc, stype, m.id desc
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
order by m.dat desc, stype, m.id desc
eof;

    public string $sqlx03 = <<<'eof'
select tab.oname||' / '||tab.aname saccount,
       tab.id sid,
       case c.code when 'PLN' then '' else c.code end swal,
       to_char(sum(tab.bo), :mask1) nbo,
       to_char(sum(tab.val), :mask1) nsaldo,
       to_char(sum(tab.val+tab.lt), :mask1) ndost
from
(
select o.name oname,
       a.name aname,
       a.id,
       a.bo,
       a.bo val,
       a.lt,
       a.currency_id
from account a
join organization o on o.id = a.organization_id
union all
select o.name oname,
       a.name aname,
       a.id,
       0 bo,
       -m.value val,
       0 lt,
       a.currency_id
from minus m
join account a on a.id = m.account_id
join organization o on o.id = a.organization_id
union all
select o.name oname,
       a.name aname,
       a.id,
       0 bo,
       p.value val,
       0 lt,
       a.currency_id
from plus p
join account a on a.id = p.account_id
join organization o on o.id = a.organization_id
union all
select o.name oname,
       a.name aname,
       a.id,
       0 bo,
       -pm.value val,
       0 lt,
       a.currency_id
from move pm
join account a on a.id = pm.accminus_id
join organization o on o.id = a.organization_id
union all
select o.name oname,
       a.name aname,
       a.id,
       0 bo,
       pm.value val,
       0 lt,
       a.currency_id
from move pm
join account a on a.id = pm.accplus_id
join organization o on o.id = a.organization_id
) tab
join currency c on c.id = tab.currency_id
group by tab.oname||' / '||tab.aname, tab.id, c.code
order by saccount
eof;

    public string $sqlx03_2 = <<<'eof'
select k.name||' / '||t.name stype,
       to_char(-m.value, :mask1) nvalue,
       to_char(m.dat, :mask3) sdata,
       m.comment scomm
from minus m
join type t on t.id = m.type_id
join kind k on k.id = t.kind_id
where m.account_id = :id
union all
select '+' stype,
       to_char(p.value, :mask1) nvalue,
       to_char(p.dat, :mask3) sdata,
       p.comment scomm
from plus p
where p.account_id = :id
union all
select '+-' stype,
       to_char(pm.value, :mask1) nvalue,
       to_char(pm.dat, :mask3) sdata,
       pm.comment scomm
from move pm
where pm.accplus_id = :id
union all
select '+-' stype,
       to_char(-pm.value, :mask1) nvalue,
       to_char(pm.dat, :mask3) sdata,
       pm.comment scomm
from move pm
where pm.accminus_id = :id
order by sdata desc, stype
eof;
}
