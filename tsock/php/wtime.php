<?php

require_once('/opt/kwynn/kwutils.php');

$keepa = true;

if (0 || isAWS()) {
    $server = 'kwynn.com';
    $url    = '/t/20/10/timeserver/nstimeraw.php';
}
else {
    $server = '127.0.0.1';
    $url    = '/tsnano/nstimeraw.php';
}

if ($keepa) {
    $sr = socket_create(AF_INET, SOCK_STREAM, 6);
    kwas(socket_connect($sr, $server, 80), 's conn failed - 620');
    $req = '';
    $req .= 'GET ' . $url . ' HTTP/1.1' . "\r\n";
    $req .= 'Host: ' .  $server . "\r\n";
    $req .= 'Connection: keep-alive' . "\r\n";
    $req .= "\r\n";
} else
    $req = 'http://' . $server . '/' . $url;

for($i=0; $i < 8; $i++) nanotime();

for($i=0; $i < 20; $i++) {
    $b   = nanotime();
    if ($keepa) {
	socket_write($sr, $req);
	$htr = socket_read($sr, 2000);
    } else $r = file_get_contents($req);
    $e = nanotime();
    if ($keepa) $r = getTime($htr);
    echo("$b\n$r\n$e\n");
}

function getTime($din) {
   $pts = explode("\r\n\r\n", $din);
   return (int)trim($pts[1]);
    
}