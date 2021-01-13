<?php

require_once('sntp.php');

$iter = 200;
$sdo = new stddev();

for($i=0; $i < $iter; $i++) {
    $va = sntp_sa::get();
    $v  = $va['calcs']['coffset'];
    $vd = sprintf('%+0.9f', $v);
    echo($vd . "\n");
    $sdo->put($v);
}
var_dump($sdo->get());
