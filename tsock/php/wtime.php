<?php

for($i=0; $i < 20; $i++) nanotime();

for($i=0; $i < 20; $i++) {
$b   = nanotime();
$stis = file_get_contents('http://[2600:1f18:23ab:9500:acc1:69c5:2674:8c03]/t/20/10/timeserver/nstimeraw.php');
$e = nanotime();
$sti = (int)$stis;
$avg = ($b + $e) >> 1;
$d = $sti - $avg;
$dd = number_format($d);
$nd = $e - $b;
$ndd = number_format($nd);
echo($dd . ' ' . $ndd . "\n");
}