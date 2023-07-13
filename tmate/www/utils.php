<?php

function tmate_get_vinord() : string {
	$t = file_get_contents('php://input');
	kwas($t && is_string($t) && strlen($t) <= tmate_config::maxstrlen, 'bad input - tmate server receive'); 
	kwas(preg_match(tmate_config::resrw, $t, $m), 'did not get valid ssh');	
	return $t;
}
