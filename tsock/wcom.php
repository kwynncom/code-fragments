<?php

require_once('/opt/kwynn/kwutils.php');

function doit($dirin) {
if (0) $type = 'loc';
else   $type = 'kwy';


$res = shell_exec($dirin . '/w');
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
    
    if ($type === 'kwy') $odf = '%+06.2f';
    else		 $odf = '%+06.3f';
    $od  = sprintf($odf, $oms);

    $ndns = $e - $b;
    $ta['n'] = $ndns;
    $ndms = $ndns / M_MILLION;
    if ($ndms > 99.92) continue;
    
    if ($type === 'kwy') $nf = '%02d';
    else		 $nf = '%07.5f';
    $ndd = sprintf($nf, $ndms);
    $ds = $od . ' ' . $ndd . "\n"; 
    // echo($ds);
    $ta['d'] = $ds;
    $a20[] = $ta;
}

usort($a20, 'vsntp_sort');

foreach($a20 as $a) echo($a['d']);
}

function vsntp_sort($a, $b) {
    return -($a['n'] - $b['n']);
}

