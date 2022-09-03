<?php

function startEndWeekDay(string $date): array
{
  $arr = [];
  $dayofweek = date('w', (int)strtotime($date));

  if ($dayofweek == 0) {
    $dayofweek = $dayofweek + 7;
  }

  $firstDayOfWeek    = date('Y-m-d', (int)strtotime((1 - $dayofweek).' day', (int)strtotime($date)));
  $lastDayOfWeek    = date('Y-m-d', (int)strtotime((7 - $dayofweek).' day', (int)strtotime($date)));

  $arr["week_first_day"] = $firstDayOfWeek;
  $arr["week_last_day"] = $lastDayOfWeek;

  return $arr;
}
