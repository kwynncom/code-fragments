<?php

// echo(shell_exec('find ~ | head')); // takes forever
// echo(shell_exec('find /var/kwynn')); // testing

$b = trim(shell_exec('echo ~')) . '/';
echo($b . "\n");



$h = popen('find ~ -maxdepth 1 ', 'r');
while($s = fgets($h)) echo($s);
pclose($h);
