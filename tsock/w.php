<?php

require_once('/opt/kwynn/kwutils.php');

doit();

function doit() {

$res = file_get_contents("php://stdin");
$a = explode("\n", trim($res));

$a20 = [];

$acnt = count($a);
if (0 && $acnt % 4 === 0) {
    $s2 = true;
    $groupn = 4;
}
else {
    $s2 = false;
    $groupn = 3;
}

for($i=0; $i < $acnt; $i += $groupn) {

    $b = $a[0 + $i];
    if (!$s2) { 
	$stis = $a[1 + $i];
	$e = $a[2 + $i];
    }
    else {
	$stis = ($a[1 + $i] + $a[2 + $i]) >> 1;
	$e = $a[$i + 3];
    }


    $sti = (int)$stis;
    $avg = ($b + $e) >> 1;
    $ons = -($sti - $avg);
    $ta['o'] = $ons;

    if (abs($ons) > M_MILLION) {
	$odf = '%+06.2f';
	$nf  = '%02d';
    } else {
	$odf = '%+08.5f';
	$nf  = '%05.3f';
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