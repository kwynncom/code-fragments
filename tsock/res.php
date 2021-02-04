<?php

// php cp.php | ./a.out | php res.php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/../sntp/sntp.php');

$f = "php://stdin";
// $f = './tf1.bin';
$r = file_get_contents($f);
kwas(strlen($r) === 64, 'bad result len - sntp raw C version 112');
$pa = unpack('q2', $r);
$snp = substr($r, PHP_INT_SIZE * 2);
$sa = sntp_get_actual::getCalcs($snp);
var_dump($sa);
exit(0);
