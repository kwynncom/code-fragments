<?php

require_once('/opt/kwynn/kwutils.php');

$sr = socket_create (AF_INET, SOCK_DGRAM, SOL_UDP);
kwas(socket_connect($sr, '127.0.0.1', 42855), 's conn failed - 620');
socket_write($sr, 'time');
// $dat = socket_read($sr, 8);
// var_dump($dat);
