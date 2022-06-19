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

$rootsz = strlen($root);
$i = 0;

$ht = '';
$ht .= file_get_contents(__DIR__ . '/top.html');

foreach($a20 as $r) {
	
	if (!trim($r)) continue;
	
	if (!$r) {
		$l = strlen($r);
		kwynn();
	}
	
	$re = '/(\S+)\s+(\S+.*)/';
	preg_match($re, $r, $ms);

	if (!$ms) {
		kwynn();
	}

	$hu = $ms[1];
	$hu = str_replace('+', ' ', $hu);
	$ts = strtotime($hu);
	$rp = $ms[2];
	$p = substr($rp, $rootsz);
	unset($a20);
	// print_r(get_defined_vars());	
	
	$ht .= '<tr>';
	$ht .= '<td>';
	$ht .= '<a href="' . $p . '">' . $p . '</a>';
	$ht .= '</td>';
	$ht .= '</tr>' . "\n";
	
	// if (++$i > 0) break;
	continue;
}

$ht .= "</table>\n</body>\n</html>\n";
echo($ht);

exit(0);
}

t1();

