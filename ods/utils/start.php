<?php

require_once('config.php');

$dir = realpath(__DIR__ . '/../');
$c = 'php -S ' . DEV_HOST . ':' . DEV_PORT . ' -t ' . $dir;

shell_exec('pkill -x -f ' . '"' . $c . '"');
shell_exec($c);
