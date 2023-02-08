<?php

use Carbon\Carbon;

function duration($value): string
{
    $min = floor($value / 60);
    if ($min < 10)
        $min = '0'.$min;
    $sec = (int)($value - ($min * 60));
    if ($sec < 10)
        $sec = '0'.$sec;
    return "$min:$sec";
}


function format_date($date, $format='d.m.Y') 
{
    return Carbon::parse($date)->format($format);
}
