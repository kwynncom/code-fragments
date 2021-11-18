<?php

$cp = 'date';

if (!isxon()) {
	echo(shell_exec($cp));
	exit(0);
}

$f = '/tmp/st3';
$h = fopen($f, 'w+');
fflush($h);

$c = $cp . "\n";
fwrite($h, $c, strlen($c));

$ih = fopen('/tmp/o', 'r');
if (($rr = fread ($ih, 3)) !== 'OK4') die('not OK = ' . $rr);
$resf = '/tmp/' . md5($cp);
$r = file_get_contents($resf);
unlink($resf);
echo($r);
exit(0);

function isxon() { 
	$f = 'xdebug_is_debugger_active';
	return function_exists($f) && $f();
} 
