select r.*
  , cqs.CurStatus
  , prevcqs.CurStatus PrevCurStatus
from (
  select u.EID
    , ht.CID
    , ht.UpdatedAt ModifyDate
    , ht.QID
    , ht.QSID
    , lag(ht.QSID) over (partition by ht.CID order by ht.UpdatedAt desc) PrevQSID
  from HistoryTable ht with(nolock)
  join Users u with(nolock) on ht.UserMod = u.UserID
  where exists (
    select 1
    from HistoryTable ch with(nolock)
    join Users u with(nolock) on ch.UserMod = u.UserID
    join ETable e with(nolock) on u.EID = e.EID
    where UpdatedAt >= @start_date
      and ch.QID in (1, 5, 7, 12)
      and ht.CID = ch.CID
    )
  ) r
join StatusTable cqs with(nolock) on r.QSID = cqs.QSID
join StatusTable prevcqs with(nolock) on r.PrevQSID = prevcqs.QSID
where 1 = 1
  and r.EID in (select EID from ETable)
  and r.PrevQSID in (select QSID from PendingTable)
  and r.QSID not in (select QSID from PendingTable)
  and r.ModifyDate >= @start_date