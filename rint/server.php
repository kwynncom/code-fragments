<?php

require_once('/opt/kwynn/kwutils.php');

kwjae(doit());

function doit() {
	
	$notes = '';
	
	$fs = ['min', 'max'];

	$vs =  [];
	foreach($fs as $i => $f) {
		try {
			$t = isrv($f);   kwas(is_numeric($t), 'not numeric');
			$t = intval($t); kwas(is_integer($t), 'not integer');
		} catch(Exception $ex) { 
			$notes = 'got bad min / max data end defaulted';
			if ($i === 0) $t = 1;
			else		  $t = 10;
		}

		$vs[] = $t;
	} unset($ex, $i);

	$min = $vs[0];
	$max = $vs[1]; unset($vs, $t, $fs, $f);


	$random = random_int($min, $max);
	$at = nanopk(NANOPK_U | NANOPK_UNSOI | NANOPK_PID);
	
	extract($at); unset($at);
	$Ums = $U * 1000 + roint($Unsoi / M_MILLION);
	
	// Sat, 27 Aug 2022 00:58:11 -0400 (+0.845063407s, core #8)
	$dhust = date('r' , $U) . ' (+0.' . $Unsoi . 's, core #' . $pid . ')';
	
	$vars = get_defined_vars();

	return $vars; 
} 
