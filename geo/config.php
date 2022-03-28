<?php

require_once('cookie/location.php');

function getMapSettings() {
	$a = locSessCl::getArrFromCookie();
	if ($a) {
		$a['zoom'] = 12;
		$a['isdefaultLoc'] = false;
		return $a;
	}
	$a = [];
	$a['ia'] = [];
	$a['ia'][0] =  33.58;
	$a['ia'][1] = -78   ;
	$a['zoom'] = 4.5;
	$a['isdefaultLoc'] = true;

	return $a;
	
}