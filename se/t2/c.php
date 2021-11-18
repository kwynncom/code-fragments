<?php

$works = 'iping > /tmp/f4 2>&1 & echo $! ';
$pid = trim(shell_exec($works));
echo($pid);
$cmd = "tail -f --pid=$pid /dev/null";
shell_exec($cmd);
echo(file_get_contents('/tmp/f4'));
