<?php

require_once('/opt/kwynn/kwutils.php');

$n = M_MILLION;

if (0) $t = shell_exec(__DIR__ . '/C/t1');
else   $t = shell_exec(__DIR__ . '/../../sts/client.bin');
if (0) {  // results 1
	$a = explode("\n", $t); unset($t);
	if (0) echo(number_format(($a[$n * 2 - 1] - $a[0]) / $n));
	else   echo(number_format(($a[$n - 1] - $a[0]) / $n));
}
else if (0) { 
	$l = strlen($t);
	echo(number_format((decodeSNTPP($t, $n * 48 - 8, 'N2') - decodeSNTPP($t, 32, 'N2')) / $n));
} else if (0) {
	echo(number_format((decodeSNTPP($t, $n * 8 - 8, 'Q') - decodeSNTPP($t, 0, 'Q')) / $n));	
} else if (0) {
	$tot = 0;
	for ($i=0; $i < $n; $i++) {
		$tot += decodeSNTPP($t, $i * 48 - 8, 'N2') - decodeSNTPP($t, $i * 48 - 16, 'N2');
	}
	echo(number_format($tot / $n));
} else {
	$a = explode("\n", $t); unset($t);	
	kwas(count($a) - 1 === $n, 'bad time server array count');
	echo(number_format(($a[$n - 1] - $a[0]) / $n));
}

exit(0);

function decodeSNTPP($p, $off, $unf) {

	static $UminusNTP = 2208988800;
	static $full32    = 4294967295;

	$lp = substr($p, $off, 8);
	$upn = unpack($unf, $lp);

	$un1 = $upn[1];

	if ($unf === 'N2') {
		$su   = $un1 - $UminusNTP;
		$fr = $upn[2] / $full32;
		$ns = $su * M_BILLION + intval(round($fr * M_BILLION));
		$ret = $ns;
	} else if ($unf === 'Q') $ret = $upn[1];

	return $ret;
}