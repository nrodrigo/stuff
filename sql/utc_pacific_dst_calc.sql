declare @fdate datetime = getdate()

-- UTC to Pacific Time Offset Calculation Rules:
-- If month is less than March and greater than November, we are not in DST
-- If month is greater than March and less than November, we are in DST
-- In March if we are >= the first Sunday of the month, we are in DST
-- In November if we are less than the first Sunday of the month, we are in DST
select case when datepart(m, @fdate) < 3 and datepart(m, @fdate) > 11
    then 8
    when datepart(m, @fdate) > 3 and datepart(m, @fdate) < 11
    then 7
    when datepart(m, @fdate) = 3
    then
      -- day of previous sunday 
      case when datepart(d, dateadd(day, -1 * (datepart(dw, @fdate) - 1), @fdate)) >= 8
      then 7
      else 8
      end
    else -- November
      -- day of the first sunday of the month
      case when datepart(d, @fdate) < datepart(d, dateadd(d, 8 - datepart(dw, dateadd(m, datediff(m, 0, @fdate), 0)), dateadd(m, datediff(m, 0, @fdate), 0)))
      then 7
      else 8
      end
    end Offset
