<?php

require_once('/opt/kwynn/kwutils.php');

$path = $argv[1];
$gz = file_get_contents($path . 'gnucash.xml.gnucash'); unset($path);
// $l = strlen($gz);
$x  = gzdecode($gz); unset($gz);
$o = getDOMO($x); unset($x);
exit(0);

