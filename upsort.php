<?php

if (time() > strtotime('2022-06-19 03:59')) die('expired');

function t1() {

$root = $_SERVER['DOCUMENT_ROOT'];

$c = 'find ' . $root . '/ ' . ' -type f -printf "%T+\t%p\n" | sort -r ';
$res = shell_exec($c);
$a = explode("\n", $res); unset($res);


$a20 = [];
foreach($a as $r) {
	if (preg_match('/\/\.git\//', $r)) continue;
	$a20[] = $r;
} unset($a);

$i = 0;
foreach($a20 as $r) {
	$re = '/(\S+)\s+(\S+.*)/';
	preg_match($re, $r, $ms);
	$hu = $ms[1];
	$hu = str_replace('+', ' ', $hu);
	$ts = strtotime($hu);
	$rp = $ms[2];
	unset($a20);
	print_r(get_defined_vars());	
	
	if (++$i > 0) break;
	continue;
}
exit(0);
}

t1();

