<?php
$finn = '/tmp/inn';
$fout = '/tmp/out';

$rinn = fopen($finn, 'w+');
// $rout = fopen($fout, 'r' );
if (!$rinn) die('open fail');
if (!fwrite($rinn, 'x', 1)) die('write fail');
echo(file_get_contents($fout));
fclose($rinn);
// fclose($rout);
