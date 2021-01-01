<?php

function dist($sin) {
    $a = str_split($sin); unset($sin);
    $r = [];
    foreach($a as $c) {
	$o = ord($c);
	if (!isset($r[$o])) $r[$o] = 0;
	$r[$o]++;
    }
    
    var_dump(['a' => $r, 'n' => count($r)]);
}

dist(random_bytes(2000));