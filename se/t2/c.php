<?php

$works = 'iping > /dev/null &';
shell_exec($works);
$h = fopen('/tmp/f5', 'r');
echo(fread($h, 493));
