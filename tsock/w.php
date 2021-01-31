<?php

require_once('/opt/kwynn/kwutils.php');

$res = shell_exec(__DIR__ . '/w');
$a = explode("\n", trim($res));

$a20 = [];

for($i=0; $i < count($a); $i += 3) {

    $b = $a[0 + $i];
    $stis = $a[1 + $i];
    $e = $a[2 + $i];

    $sti = (int)$stis;
    $avg = ($b + $e) >> 1;
    $ons = -($sti - $avg);
    $ta['o'] = $ons;

    $oms = $ons / M_MILLION;
    $od  = sprintf('%+06.2f', $oms);

    $ndns = $e - $b;
    $ta['n'] = $ndns;
    $ndms = $ndns / M_MILLION;
    if ($ndms > 99.92) continue;
    $ndd = sprintf('%02d', $ndms);
    $ds = $od . ' ' . $ndd . "\n"; 
    // echo($ds);
    $ta['d'] = $ds;
    $a20[] = $ta;
}

function vsntp_sort($a, $b) {
    return -($a['n'] - $b['n']);
}

usort($a20, 'vsntp_sort');

foreach($a20 as $a) echo($a['d']);