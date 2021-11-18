<?php

require_once('/opt/kwynn/kwutils.php');

$f = '/tmp/st3';

echo("v526\n");
while (1) {
	$h = fopen($f, 'r');
	$t = trim(fgets($h));
	fclose($h);
	if (!$t) continue;
	$r = shell_exec($t);
	$oh = fopen('/tmp/o', 'w+');
	fwrite($oh, $r, strlen($r));
	fflush($oh);
	fclose($oh);
}
