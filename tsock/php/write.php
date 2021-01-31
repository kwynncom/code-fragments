<?php

require_once('/opt/kwynn/kwutils.php');

$sr = socket_create (AF_INET, SOCK_DGRAM, SOL_UDP);
kwas(socket_connect($sr, '127.0.0.1', 42855), 's conn failed - 620');

for($i=0; $i < 10; $i++) nanotime();

$b = nanotime();
socket_write($sr, 'time');
$stis = socket_read($sr, 19);
$e = nanotime();


$sti = (int)$stis;
$avg = ($b + $e) >> 1;
$d = $sti - $avg;
$dd = number_format($d);
$nd = $e - $b;
$ndd = number_format($nd);
echo($dd . ' ' . $ndd . "\n");

