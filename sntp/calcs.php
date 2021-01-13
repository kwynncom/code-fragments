<?php

require_once('sntp.php');

$iter = 1;
$sdo = new stddev();
$geto = new sntp_sa();

for($i=0; $i < $iter; $i++) {
    $va = $geto->get();
    $v  = $va['calcs']['coffset'];
    $vd = sprintf('%+0.9f', $v);
    echo($vd . "\n");
    $sdo->put($v);
}
var_dump($sdo->get());
