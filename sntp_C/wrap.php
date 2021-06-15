<?php

require_once('./../sntp/sntp.php');

doit();

function doit() {

$slen = 48 + 8 + 8;

$r = popen('./sntp', 'r');
$s = fread($r, $slen); kwas(strlen($s) === $slen, "sntp wrap fread not $slen bytes");
pclose($r); unset($r);
$rr = sntp_get_actual::b8tonano($s, 32, 'N');
$rs = sntp_get_actual::b8tonano($s, 40, 'N');
$bb = substr($s, 48, 8);
$ba  = unpack('Q', $bb);
$ea = unpack('Q', substr($s, 56, 8));
$b = $ba[1];
$e = $ea[1];
$vars = get_defined_vars();
$fs = ['b', 'rr', 'rs', 'e'];
foreach($fs as $f) echo(number_format($vars[$f]) . "\n");

exit(0);
}