<?php

require_once('/opt/kwynn/kwutils.php');

$res = shell_exec(__DIR__ . '/w');
$a = explode("\n", trim($res));

for($i=0; $i < count($a); $i += 3) {

    $b = $a[0 + $i];
    $stis = $a[1 + $i];
    $e = $a[2 + $i];

    $sti = (int)$stis;
    $avg = ($b + $e) >> 1;
    $ons = $sti - $avg;

    $oms = $ons / M_MILLION;
    $od  = sprintf('%6.2f', $oms);

    $ndns = $e - $b;
    $ndms = $ndns / M_MILLION;
    $ndd = sprintf('%02d', $ndms);
    echo($od . ' ' . $ndd . "\n");
}