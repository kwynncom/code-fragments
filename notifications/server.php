<?php

require_once('/opt/kwynn/kwutils.php');

$a = kwjssrp();

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $a['endpoint']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

$d = ['body' => 'hi'];
$j = json_encode($d);
$l = strlen($j);
$h = ['TTL: 60']; // , 'Content-Type: text/plain', "Content-Length: $l", 'Content-Encoding: utf8'];
curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
// curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content: text/plain']);

// curl_setopt($ch, CURLOPT_POSTFIELDS, $j);
$server_output = curl_exec($ch);

$res = curl_getinfo($ch);

exit(0);

