<?php

function ebo($i) {
    
    static $pow = 1.2;
    
    $v10 = 4 - $pow + pow(1.2, $i);
    $v20 = round($v10);
    return $v20;
}

for($i=0; $i < 50; $i++) echo(ebo($i) . " $i\n");