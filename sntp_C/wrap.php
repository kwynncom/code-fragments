<?php

require_once('./../sntp/sntp.php');

doit();

function doit() {

$count = 1;
$itlen = 48; //  + 8 + 8;
$totlen = $itlen * $count;

$r = popen('./sntp', 'r');
$wr = fread($r, $totlen); kwas(strlen($wr) === $totlen, "sntp wrap fread not $totlen bytes");
pclose($r); unset($r);

for($i=0; $i < $count; $i++) {



$s = substr($wr, $itlen * $i, $itlen);

 mytest(substr($wr, 0, 48));
exit(0);

$unpch = 'KwC1';

// sntp_get_actual::b8tonsC($s, 24, $unpch); // test outgoing

$rr =  sntp_get_actual::b8tosf($s, 32, $ignore, 'blah', 'blah', 'N');
$rr = sntp_get_actual::b8tonsC($s, 32, $unpch);
$rs = sntp_get_actual::b8tonsC($s, 40, $unpch);
$bb = substr($s, 48, 8);
$ba  = unpack('Q', $bb);
$ea = unpack('Q', substr($s, 56, 8));
$b = $ba[1];
$e = $ea[1];
$vars = get_defined_vars();
$fs = ['b', 'rr', 'rs', 'e'];
foreach($fs as $f) echo(number_format($vars[$f]) . "\n");
echo("\n");
}
exit(0);
}

function mytest($p) {
	for($i=5; $i < 6; $i++) {
		//for($j=0; $j < 8; $j++) {
			$lp = substr($p, 44, 4);
			$upn = unpack('N', $lp);
			$d = $upn[1];
			$b10 = sprintf('%032s', decbin($d));
			$br10 = '';
			for($i=0; $i < 32; $i++) $br10[$i] = $b10[32- $i - 1];
			$d20 = bindec($br10);
			$p20 = pack('N', $d20);
			$upn20 = unpack('N', $p20);
			$d30 = $upn20[1];
			echo($d30 . "\n");
			// echo(bindec($br10) . "\n");
			
		// }
		echo("\n");
	}
}
