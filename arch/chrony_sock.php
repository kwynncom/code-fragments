<?php

require_once('/opt/kwynn/kwutils.php');

$socket = fsockopen('udp://localhost', 323, $err_no, $err_str,1);
$cmd = pack('Q', 33); // 33 is REQ_TRACKING per chrony source code
$fpr = fputs($socket, $cmd);
$ret = fread($socket, 1);


if (0) {
    $sr = socket_create (AF_UNIX, SOCK_DGRAM, 0); // appears to be the only kind that works for AF_UNIX
    kwas(socket_bind($sr, '/var/run/chronyd.sock'), 'bind fail'); // connects with root
}