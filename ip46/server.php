<?php

require_once('/opt/kwynn/kwutils.php');
if (ispkwd()) $o = $_SERVER['HTTP_ORIGIN'];
else $o = 'https://kwynn.com';

header("Access-Control-Allow-Origin: $o");
header('Content-Type: text/plain');

echo(kwifs($_SERVER, 'REMOTE_ADDR'));
