<?php

$finn = '/tmp/inn';
$fout = '/tmp/out';



while(1) {
	$rinn = fopen($finn, 'r');
	if (!$rinn) die('rinn open fail');
	$t = fread($rinn, 1);
	$l = strlen($t);
	$us = microtime(1);
	echo('read' . " $l $us\n");
	// if (strlen($t) !== 1) die('read fail');
	fclose($rinn);
	if (!$l) continue;
	// $hu = date('r') . "\n";
	$sout = shell_exec('iping');
	$l  = strlen($sout);
	$rout = fopen($fout, 'w+' );
	if (!$rout) die('rout open fail');
	sleep(5);
	if (fwrite($rout, $sout, $l) !== $l) die('write fail');
	fflush($rout);
	fclose($rout);
}
