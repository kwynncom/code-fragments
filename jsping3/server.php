<?php 

header('Content-Type: text/plain');

$out = '';
if (1) $out = intval(round(microtime(1) * 1000));

echo($out);
unset($out);