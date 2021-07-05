#! /usr/bin/php
<?php

$max = 600;
$i   = 0;

$ipvs = [4, 6];

$gotv[4] = -5000;
$gotv[6] = -4000;

$ok = false;

do {
	foreach($ipvs as $ipv) {
		echo("IPv$ipv: ");
		$cmd = 'ping -' . $ipv . ' ' . ' -c 1 kwynn.com' . ' | ' . 'head -n 2';
		$res = shell_exec($cmd);
		echo $res;
		if (preg_match('/\d+ bytes from/', $res)) { 
			echo("** IPv$ipv OK *****\n");
			$gotv[$ipv] = time();
			if (abs($gotv[4] - $gotv[6]) < 3) {
				$ok = true;
				echo("** BOTH OK ***\n");
				break 2; 
			}
		} else sleep(1);
	}

} while(++$i < $max);

if (!$ok) {
    echo("gave up after $max tries\n");
    exit(53); // arbitrary error number
}

/* initial test:
php iping.php
* install and test:
chmod 755 iping.php
sudo cp iping.php /usr/bin/iping
iping
*/
