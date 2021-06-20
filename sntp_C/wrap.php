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
	for($i=0; $i < 6; $i++) {
		for($j=0; $j < 8; $j++) echo(sprintf('%02s', 
			dechex(
					ord($p[$j + $i * 8])) 
			) 
			. ' ');

		$hex = substr($p, 40, 8);
		echo(' ' . bin2hex($hex));
		echo("\n");
	}
}
