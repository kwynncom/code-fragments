<?php

require_once('./../sntp/sntp.php');

$r = popen('./sntp', 'r');
$s = fread($r, 48); kwas(strlen($s) === 48, 'popen not 48 bytes');
pclose($r); unset($r);
$a = [];
sntp_get_actual::b8tosf($s, 40, $a, 's', 'f');
echo($a['s'] + $a['f'] . "\n");
