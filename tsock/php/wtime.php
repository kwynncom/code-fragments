<?php

require_once('/opt/kwynn/kwutils.php');


for($i=0; $i < 20; $i++) nanotime();

$sr = socket_create(AF_INET, SOCK_STREAM, 6);
kwas(socket_connect($sr, 'kwynn.com', 80), 's conn failed - 620');

$req = '';
$req .= 'GET /t/20/10/timeserver/nstimeraw.php' . ' HTTP/1.1' . "\r\n";
$req .= 'Host: kwynn.com' . "\r\n";
$req .= 'Connection: keep-alive' . "\r\n";
$req .= "\r\n";

for($i=0; $i < 20; $i++) {
$b   = nanotime();
socket_write($sr, $req);
$htr = socket_read($sr, 2000);
$e = nanotime();
$sti = getTime($htr); unset($htr);
$avg = ($b + $e) >> 1;
$d = $sti - $avg;
$dd = number_format($d);
$nd = $e - $b;
$ndd = number_format($nd);
echo($dd . ' ' . $ndd . "\n");
}

function getTime($din) {
   $pts = explode("\r\n\r\n", $din);
   return (int)trim($pts[1]);
    
}