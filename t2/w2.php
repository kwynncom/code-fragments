<?php

$n = 1000;
$t = shell_exec(__DIR__ . '/C/t1');
$a = explode("\n", $t);
for ($i=1; $i <= $n; $i += 2) 
	echo(number_format($a[$i] - $a[$i-1]) . "\n");
