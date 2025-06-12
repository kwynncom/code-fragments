<?php

// sudo -u www-data getent passwd 1000 | cut -d: -f1
// results in "bob" or the main / first / creator Ubuntu login user
// don't forget to trim it
// 
// $ visudo
// include a secret (sort of a password)
// test that the exact command must include the secret for sudo to run
// www-data mymachinename=(bob) NOPASSWD: /usr/bin/php /path/to/your/script.sh
// 
// Picky tidbits like that courtesy of Grok 3.0 2025/06/11 08:45am EDT

require_once('/opt/kwynn/kwutils.php');

$f = __DIR__ . '/../' . 'wwwdo.php';
$realPath = realpath($f); unset($f);

$cmdOrign = 'php '  . $realPath . ' html ' . ' 2>&1 ';

$user = trim(shell_exec('getent passwd 1000 | cut -d: -f1'));

kwas($user && is_string($user), 'did not get 1000 user');

$cmd = 'sudo -u ' . $user . ' /usr/bin/php ' . $realPath . ' password'; // . ' html ' . ' 2>&1 ';
echo(shell_exec($cmd));

unset($realPath);
