<?php

require_once('/opt/kwynn/kwutils.php');

function retAndElim(array &$a) : int | string {
	$si = random_int(0, count($a) - 1);
	$sel = $a[$si];
	unset($a[$si]);
	$a = array_values($a);
	return $sel;
}

function putQ(object $a) {
	$o = new dao_generic_3('IQ');
	$o->creTabs('q');
	$o->qcoll->insertOne((array)$a);
	
}