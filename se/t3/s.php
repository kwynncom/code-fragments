<?php

require_once('/opt/kwynn/kwutils.php');

$f = '/tmp/st3';

while (1) {
	$h = fopen($f, 'r');
	$t = trim(fgets($h));
	fclose($h);
	echo("\ncmd: $t\n");
	if (!$t) {
		continue;
	}
	echo('bex' . "\n");
	$r = shell_exec($t);
	echo('aex' . "\n");
	$n = nanopk();
	$of = '/tmp/' . sprintf('%020d', $n['tsc']) . '_' . sprintf('%03d', $n['pid']);
	file_put_contents($of, $r);
	echo('bwr' . "\n");
	$oh = fopen('/tmp/o', 'w+');
	fwrite($oh, $r, strlen($r));
	echo('awr' . "\n");
}
