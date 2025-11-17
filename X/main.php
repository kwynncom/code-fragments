<?php

require_once('getODS.php');
require_once('getX.php');

$f = '/tmp/' . dao_generic_4::get_oids() . '.json';
touch($f);
kwas(is_writable($f), "$f not writable");

$o = new XRatiosODSCl();


$res = getXPostData($o->xids);
$j = json_encode($res, JSON_PRETTY_PRINT);
file_put_contents($f, $j);
exit(0);
