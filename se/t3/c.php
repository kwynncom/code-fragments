<?php

$cp = 'date';

if (!isxon()) {
	echo(shell_exec($cp));
	exit(0);
}

//  filetype(string $filename): string|false
//  Returns the type of the file. Possible values are fifo, char, dir, block, link, file, socket and unknown. 

$f = '/tmp/st3';
$c = $cp . "\n";

$h = fopen($f, 'w');
fwrite($h, $c, strlen($c));	
$ih = fopen('/tmp/o', 'r');
$rr = fread ($ih, 29);
echo($rr . "\n");
exit(0);

function isxon() { 
	$f = 'xdebug_is_debugger_active';
	return function_exists($f) && $f();
} 
