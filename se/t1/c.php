<?php

require_once('/opt/kwynn/lock.php');
$lo = new sem_lock(__FILE__);

$finn = '/tmp/inn';
$fout = '/tmp/out';

$lo->lock();
$rinn = fopen($finn, 'w+');
if (!$rinn) die('open fail');
if (!fwrite($rinn, 'x', 1)) die('write fail');
echo(file_get_contents($fout));
fclose($rinn);
$lo->unlock();
unset($lo);