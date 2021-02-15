#! /usr/bin/php
<?php // init ping - ping until success or $max attempts see installation notes below

$max = 600;
$i   = 0;

$ipvs = [4, 6];
$ok = false;

foreach($ipvs as $ipv) 
do {
    echo("Ping attempt, IPv$ipv:\n");
    $cmd = 'ping -' . $ipv . ' ' . ' -c 1 kwynn.com' . ' | ' . 'head -n 2';
    $res = shell_exec($cmd);
    echo $res;
    if (preg_match('/\d+ bytes from/', $res)) { 
	echo("******************* IPv$ipv OK *****\n");
	$ok = true; 
	break; 
	
    }
    sleep(1);
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
