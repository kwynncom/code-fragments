<?php

$f = '/tmp/tmate.txt';

$t = file_get_contents('php://input');
header('Content-Type: text/plain');
echo($t . "\n");
file_put_contents($f, '', FILE_APPEND);
chmod($f,0660);
file_put_contents($f, $t, FILE_APPEND);
