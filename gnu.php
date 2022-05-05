<?php

require_once('/opt/kwynn/kwutils.php');

$path = $argv[1];
$gz = file_get_contents($path . 'gnucash.xml.gnucash'); unset($path);
// $l = strlen($gz);
$x  = gzdecode($gz); unset($gz);
$o =  XMLReader::XML($x); unset($x);
// $o->read();
for($i=0; $i < 100000; $i++) {
	echo($o->name . "\n");
	$o->read();
}
exit(0);

