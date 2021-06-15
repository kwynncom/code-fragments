<?php

require_once('./../sntp/sntp.php');

$s = trim(shell_exec('./a.out'));
$l = strlen($s);

// public static function b8tosf($bin, $o, &$aref, $sl, $fl) 
$a = [];
sntp_get_actual::b8tosf($s, 40, $a, 's', 'f');

echo($a['s'] + $a['f'] . "\n");

exit(0);