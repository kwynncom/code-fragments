<?php

require_once('/opt/kwynn/kwutils.php');

function kwCalInitDate() {
    $a = [];
    $now = time();
    $a['monthh1'] = date('F, Y', $now);
    $day1 = date('Y-m-', $now) . '01';
    $a['day1w'  ] = intval(date('w', strtotime($day1)));
    $a['dinm'] = intval(date('t', $now));
    
    return($a);
}

if (didCLICallMe(__FILE__)) var_dump(kwCalInitDate());
