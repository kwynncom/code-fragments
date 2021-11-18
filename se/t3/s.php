<?php

require_once('/opt/kwynn/kwutils.php');

$f = '/tmp/st3';

	$oh = fopen('/tmp/o', 'w+');

while (1) {
	$h = fopen($f, 'r');
	$t = trim(fgets($h));
	fclose($h);
	echo("\ncmd: $t\n");
	if ($t !== 'date') {
		continue;
	}
	echo('bex' . "\n");
	$r = shell_exec($t);
	echo('aex' . "\n");
	$n = nanopk();
	$of = '/tmp/' . sprintf('%020d', $n['tsc']) . '_' . sprintf('%03d', $n['pid']);
	file_put_contents($of, $r);
	echo('bwr' . "\n");
	kwas(fwrite($oh, $of, 29) === 29, 'bad write');
	echo('awr' . "\n");
	// fclose($oh);
}
