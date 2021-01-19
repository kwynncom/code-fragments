<?php

require_once('get.php');
require_once('/opt/kwynn/kwcod.php');

$iter = 0;
$sdo = new stddev();
$geto = new ntpGet();

for($i=0; $i < $iter; $i++) {
    $va = $geto->get();
    $v  = $va['calcs']['coffset'];
    $vd = sprintf('%+0.9f', $v);
    echo($vd . "\n");
    $sdo->put($v);
}
var_dump($sdo->get());
