#! /usr/bin/php
<?php // init ping - ping until success or $max attempts see installation notes below

$max = 300;
$i   = 0;

do {
    echo("Ping attempt:\n");
    $cmd = 'ping -c 1 kwynn.com' . ' | ' . 'head -n 2';
    $res = shell_exec($cmd);
    echo $res;
    if (preg_match('/\d+ bytes from/', $res)) exit(0);
    sleep(1);
} while(++$i < $max);

echo("gave up after $max tries\n");
exit(53); // arbitrary error number

/* initial test:
php iping.php
* install and test:
chmod 755 iping.php
sudo cp iping.php /usr/bin/iping
iping
*/
