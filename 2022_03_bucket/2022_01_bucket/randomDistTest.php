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

$in = random_bytes(2000); 
$in = file_get_contents('/tmp/cryd');

dist($in);