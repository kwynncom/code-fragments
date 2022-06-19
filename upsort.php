<?php

if (time() > strtotime('2022-06-19 02:59')) die('expired');

$root = $_SERVER['DOCUMENT_ROOT'];

$c = 'find ' . $root . '/ ' . ' -type f -printf "%T+\t%p\n" | sort -r ';
$res = shell_exec($c);
$a = explode("\n", $res);
echo($c);

exit(0);