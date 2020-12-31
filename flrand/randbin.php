<?php

$r = fopen('/dev/urandom', 'r');
$d = fread($r, 1024 * 100);
$l = strlen($d);
$i=0; 
while(isset($d[$i + 8])) {
    $s = '';
    for($j=0; $j < 8; $j++) $s .= $d[$i + $j];
    $ua = unpack('Q', $s);
    // echo round(log(abs($ua[1]), 10)) . ' ' . $ua[1] . "\n";
    echo(sprintf('%064s', decbin($ua[1])) . "\n");
 //   for($j=0; $j < 8; $j++) echo($s[$j]);
    $i += 8;
}