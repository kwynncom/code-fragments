<?php
// $pid = posix_getpid();
// echo(shell_exec("pstree -s $pid"));
// sleep(600);

$h = popen('tail -f /tmp/e', 'r');
while ($l = fgets($h)) {
	file_put_contents('/tmp/f', $l, FILE_APPEND);
}
pclose($h);
