<?php

// header('Access-Control-Allow-Origin: http://127.0.0.1:19999/server.php?XDEBUG_SESSION_START=netbeans-xdebug');

require_once('/opt/kwynn/kwutils.php');
if (ispkwd()) $o = $_SERVER['HTTP_ORIGIN'];
else $o = 'https://kwynn.com';

header("Access-Control-Allow-Origin: $o");
header('Content-Type: text/plain');

echo('hi');
