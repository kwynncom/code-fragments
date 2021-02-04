<?php

// php cp.php | ./a.out | php res.php
// php res.php | php w.php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/../sntp/sntp.php');

$exl = 64; // expected length

$f = "php://stdin";
// $f = './tf1.bin';
while ($r = fread(STDIN, $exl)) { 
// $r = file_get_contents($f);
kwas(strlen($r) === $exl, 'bad result len - sntp raw C version 112');
$pa = unpack('q2', $r);
$snp = substr($r, PHP_INT_SIZE * 2);
$sa = sntp_get_actual::parsePacket($snp);

if (!isset($pa[1]) || !isset($pa[2]) || !isset($sa['avg'])) die('not 3 fields C - PHP SNTP 155');

echo("$pa[1]\n$sa[avg]\n$pa[2]\n");
}
exit(0);
