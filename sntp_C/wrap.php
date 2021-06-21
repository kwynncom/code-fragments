<?php

require_once('./../sntp/sntp.php');

doit();

function doit() {

$count = 1;
$itlen = 48; //  + 8 + 8;
$totlen = $itlen * $count;

echo('1234567890123456789012345678901234567890123456789012345678901234' . "\n");
for($j=0; $j < 17; $j++) {
for($i=0; $i < $count; $i++) {
$r = popen('./sntp', 'rb');
$wr = fread($r, $totlen); kwas(strlen($wr) === $totlen, "sntp wrap fread not $totlen bytes");
pclose($r); unset($r);


 mytest(substr($wr, 0, 48));

}

sleep(1);

}
exit(0);
}

function mytest($p) {
	$lp = substr($p, 40, 8);
	$upn = unpack('Q', $lp);
	$d10 = $upn[1];

	echo($d10);

	$b10 = decbin($d10);
	$f10 = sprintf('%064s', $b10);
	if (0) echo($f10);

	if (0) echo($upn[1] . "\n");

	if(0) {
	$d = $upn[1];
	echo($upn[1] . ' ' . $upn[2] . "\n");		
	}


	if (0) {
		$s10 = $d10; //  >> 32;
		echo($s10 . "\n");

		if (0) {$u20 = unpack('V', $s10);
			$d20 = $u20[1];
		echo(decbin($d20) . "\n");}

	}

	echo("\n");

}
