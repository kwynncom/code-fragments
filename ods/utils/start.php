<?php

$dir = realpath(__DIR__ . '/../');
$c = 'php -S localhost:8000 -t ' . $dir;

shell_exec('pkill -x -f ' . '"' . $c . '"');
shell_exec($c);
