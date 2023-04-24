<?php

require_once('/opt/kwynn/kwutils.php');

function retAndElim(array &$a) : int | string {
	$si = random_int(0, count($a) - 1);
	$sel = $a[$si];
	unset($a[$si]);
	$a = array_values($a);
	return $sel;
}

function putQ(object|array &$din) {
	$din = (array)$din;
	$o = new dao_generic_3('IQ');
	$o->creTabs('q');
	$din['_id'] = dao_generic_3::get_oids();
	
	if (!iscli()) {
		$n = isrv('n'); $n = intval($n); kwas($n >= 1 && $n <= 5, 'bad task num - 1931');
		$din['taskn'] = $n;
	}
	
	$o->qcoll->insertOne((array)$din);
	$din = (object)$din;
}
