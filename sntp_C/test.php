<?php

require_once('./../sntp/sntp.php');

$s = file_get_contents('./test.txt');
$l = strlen($s);

// public static function b8tosf($bin, $o, &$aref, $sl, $fl) 
$a = [];
sntp_get_actual::b8tosf($s, 40, $a, 's', 'f');
exit(0);