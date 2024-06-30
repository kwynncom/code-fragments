<?php

require_once('/opt/kwynn/kwutils.php');

$a = kwjssrp();
$o = new dao_generic_3('IQ');
$o->creTabs('q');
dao_generic_3::oidsvd($a['_id']);
$j = json_encode($a);
$l = strlen($j);
kwas($l < 1000, 'invalid task IQ input 0019');

$q = ['_id' => $a['_id']];
if (!$o->qcoll->count($q)) exit(0); // if client and server get out of sync during testing, don't write an answer to a non-existent question 
$o->qcoll->upsert($q, $a);
