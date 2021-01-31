<?php

require_once('/opt/kwynn/kwutils.php');

$sr = socket_create (AF_INET, SOCK_DGRAM, SOL_UDP);
kwas(socket_bind($sr, '127.0.0.1', 42855), 's bind failed - 620');

for($i=0; $i < 10; $i++) nanotime();

do {
socket_recvfrom($sr, $buf, 4, 0, $remote_ip, $remote_port);
$t = nanotime();
socket_sendto($sr, $t , 19 , 0 , $remote_ip , $remote_port);
} while(true);
// socket_write($sr, $t);

/*
	$r = socket_recvfrom($sock, $buf, 512, 0, $remote_ip, $remote_port);
	echo "$remote_ip : $remote_port -- " . $buf;
	
	//Send back the data to the client
	socket_sendto($sock, "OK " . $buf , 100 , 0 , $remote_ip , $remote_port);
 * 
 * *
 */