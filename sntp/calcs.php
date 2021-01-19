<?php

require_once('get.php');
require_once('/opt/kwynn/kwcod.php');

$iter = 3;
$geto = new ntpQuotaGet();

$ras = $geto->get($iter);

$okr = $badr = [];

foreach($ras as $ra) {

    if (isset($ra['off'])) {
    	$v =  $ra['off'];
	$v *= 1000;
	$vd = sprintf('%+06.2f', $v);
	$s = ($vd . 'ms ' . $ra['srv'] .   "\n");
	$okr[] = $s;
    } else {
	$s = ($ra['status'] . ' ' . $ra['server'] . "\n");
	$badr[] = $s;
    }
}

foreach($badr as $s) echo($s);
foreach($okr  as $s) echo($s);
