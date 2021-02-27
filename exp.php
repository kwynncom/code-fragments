<?php

function ebo($i) {
    
    static $pow = 1.3;
    
    $v10 = 4 - $pow + pow($pow, $i);
    $v20 = round($v10);
    return $v20;
}

for($i=0; $i < 50; $i++) echo(ebo($i) . " $i\n");