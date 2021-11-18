<?php

$cp = 'date';

if (!isxon()) {
	echo(shell_exec($cp));
	exit(0);
}

$f = '/tmp/st3';

$c = $cp . "\n";

do {
	$h = fopen($f, 'w');
	fwrite($h, $c, strlen($c));	

	$ih = fopen('/tmp/o', 'r');
	$rr = fread ($ih, 29);
	$l = strlen($rr);
	if ($l !== 29) continue;
	if (file_exists($rr)) {
		$r = file_get_contents($rr);
		unlink($rr);
		break;
	}
} while(1);
echo($r);
exit(0);

function isxon() { 
	$f = 'xdebug_is_debugger_active';
	return function_exists($f) && $f();
} 
