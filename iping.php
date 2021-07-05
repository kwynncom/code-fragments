#! /usr/bin/php
<?php // init ping - ping until success or $max attempts see installation notes below

// Kwynn 2021/07/05 5:00pm EDT / UTC -4 - note that this version doesn't quite work.  It is betwixt and between.  The version from weeks ago works 
// fine.  Hopefully I'll change this one as I want it soon.

$max = 600;
$i   = 0;

$ipvs = [4, 6];

$gotv[4] = -5000;
$gotv[6] = -4000;

/* 2021/04/16 - The new version almost certainly does not work yet.  The intent of the new is that this will keep running until both IPv4 and IPv6 
 * ping within a few seconds of each other.  */

$ok = false;

do {
foreach($ipvs as $ipv) {
    echo("Ping attempt, IPv$ipv:\n");
    $cmd = 'ping -' . $ipv . ' ' . ' -c 1 kwynn.com' . ' | ' . 'head -n 2';
    $res = shell_exec($cmd);
    echo $res;
    if (preg_match('/\d+ bytes from/', $res)) { 
	echo("******************* IPv$ipv OK *****\n");
	$gotv[$ipv] = time();
	if (abs($gotv[4] - $gotv[6]) < 4) {$ok = true; break 2; }
}
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
