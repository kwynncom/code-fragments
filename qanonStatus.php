<?php

require_once('/opt/kwynn/kwutils.php');

// curl -I https://qanon.pub/data/json/posts.json

$url = 'https://qanon.pub/data/json/posts.json';

$jsts = roint(microtime(1) * 1000);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url . '?t=' . $jsts); // *is* necessary
curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD HTTP method for minimal work on the server
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_USERAGENT, kwua()); // works but isn't necessary
curl_setopt($ch, CURLOPT_HEADER, true);
$res = curl_exec($ch);
$sz = strlen($res);
$info = curl_getinfo($ch);

file_put_contents('/tmp/q.txt', $res);

exit(0);
