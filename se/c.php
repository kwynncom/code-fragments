<?php

$cp = 'iping';

$f = '/tmp/st3';
$c = $cp . "\n";

$h = fopen($f, 'w');
fwrite($h, $c, strlen($c));	
$ih = fopen('/tmp/o', 'r');
$rr = fread ($ih, 1000);
echo($rr);
echo('at ' . date('r') . "\n");
exit(0);


