<?php

require_once('boot.php');

$f10 = '/tmp/bootd.txt';

if (0) {
$dat = boot_tracker::get();
var_dump($dat);
file_put_contents($f10, json_encode($dat));
}
if (1) {
for ($i=0; $i < 30; $i++) {
    $b = microtime(1);
    if (0) boot_tracker::get();
    else if (0) {
	if (!file_exists($f10)) die('file should exist here - 758pm');
	$res = file_get_contents($f10);
    }
    else if (0) uptime();
    else if (1) boot_tracker::getDB();
    $e = microtime(1);
    echo(($e - $b) . "\n");
    if (isset($res)) echo(strlen($res) . "\n");
}
}


