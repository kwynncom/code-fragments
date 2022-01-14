<?php

function ebo($i) {
    
    static $pow = 1.2;
    
    $v10 = 4 - $pow + pow($pow, $i);
    $v20 = round($v10);
    return $v20;
}

$sum = 0;
for($i=0; $i < 50; $i++) {
    $ebo = ebo($i);
    $sum += $ebo;
    $pers = ($i + 1) / $sum;
    $persd = sprintf('%0.2f', $pers);
    $perd = 86400 * $pers;
    $perdd = round($perd);
    echo(ebo($i) . " $sum $i $persd $perdd\n");
}