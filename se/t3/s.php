<?php

$f = '/tmp/st3';

$h = fopen($f, 'w+');
fflush($h);

$oh = fopen('/tmp/o', 'w+');
fflush($oh);

while (1) {
	$t = trim(fgets($h));
	if ($t !== 'date') continue;
	$r = shell_exec($t);
	$md5 = md5($t);
	file_put_contents('/tmp/' . $md5, $r);
	fflush($oh);
	fwrite($oh, 'OK4', 3);
	fflush($oh);
}
