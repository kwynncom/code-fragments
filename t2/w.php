<?php
				//   123456789
define('M_BILLION', 1000000000);

$t = shell_exec('./C/t1');
if (0) {  // results 1
	$a = explode("\n", $t); unset($t);
	echo(number_format($a[1999] - $a[0]));
}
$l = strlen($t);

echo(number_format(decodeSNTPP($t, 47992, 'N2') - decodeSNTPP($t, 32, 'N2')));

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