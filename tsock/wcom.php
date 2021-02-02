<?php

require_once('/opt/kwynn/kwutils.php');

function doit($dirin) {

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

    if ($ons > M_MILLION) {
	$odf = '%+06.2f';
	$nf  = '%02d';
    } else {
	$odf = '%+08.5f';
	$nf  = '%04.2f';
    }
    
    $oms = $ons / M_MILLION;

    $od  = sprintf($odf, $oms);

    $ndns = $e - $b;
    $ta['n'] = $ndns;
    $ndms = $ndns / M_MILLION;
    if ($ndms > 99.92) continue;

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

