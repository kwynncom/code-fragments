<?php

require_once('get.php');
require_once('/opt/kwynn/kwcod.php');

$iter = 5;
$sdo = new stddev();
$geto = new ntpQuotaGet();

for($i=0; $i < $iter; $i++) {
    $ra = $geto->get();
    $v = $ra['off'];
    $vd = sprintf('%+0.9f', $v);
    echo($vd . ' ' . $ra['srv'] .   "\n");
    $sdo->put($v);
}
var_dump($sdo->get());
