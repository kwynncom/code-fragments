<?php

do10();

function do10() {

$t = shell_exec('python3 ' . __DIR__ . '/../pygnucash/main.py');
$afwd = json_decode($t, true); unset($t);
$a = array_reverse($afwd); unset($afwd);

$startBal = -1;

foreach($a as $i => $r) {
    if ($r['reconciled'] === 'y') {
	$startBal = $r['bal'];
	break;
    }
} unset($r);

$a = array_slice($a, 0, $i + 1); unset($i);
$arev = array_reverse($a);

$vars = get_defined_vars();
var_dump($vars); unset($vars);
unset($a);
$a = array_reverse($arev); 
unset($arev);
return;
}

