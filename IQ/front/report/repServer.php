<?php

require_once('/opt/kwynn/kwutils.php');

$a = kwjssrp();
$o = new dao_generic_3('IQ');
$o->creTabs('q');
dao_generic_3::oidsvd($a['_id']);
$j = json_encode($a);
$l = strlen($j);
kwas($l < 1000, 'invalid task IQ input 0019');
$o->qcoll->upsert(['_id' => $a['_id']], $a);
