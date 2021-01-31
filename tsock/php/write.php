<?php

require_once('/opt/kwynn/kwutils.php');

$sr = socket_create (AF_INET, SOCK_DGRAM, SOL_UDP);
kwas(socket_connect($sr, '127.0.0.1', 42855), 's conn failed - 620');

for($i=0; $i < 10; $i++) nanotime();

for($i=0; $i < 5; $i++) {
$b = nanotime();
socket_write($sr, 'time');
$stis = socket_read($sr, 19);
$e = nanotime();


$sti = (int)$stis;
$avg = ($b + $e) >> 1;
$ons = $sti - $avg;
$oms = $ons / M_MILLION;

$odf = '%+06.4f';
$od  = sprintf($odf, $oms);
$nd = $e - $b;
$ndd = number_format($nd);
echo($od . ' ' . $ndd . "\n");

}