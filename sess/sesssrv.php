<?php

require_once('/opt/kwynn/kwutils.php');
require_once('location.php');

function location_cc() {
	
	static $cname = 'location';

	$fa = kwjssrp();
	
	switch($fa['unit']) {
		case 'now'     : $units = -100000; break;
		case 'session' : $units = 0		 ; break;
		case '1'	   :
	    case '60'      : 
		case '3600'    : 
		case '86400'   : 
			kwas(is_numeric($fa['units']), 'bad units location 0237');
			$units = intval($fa['unit']) * $fa['units'];
			break;
		default : kwas(false, 'invalid unit sent location 0238');
		
	}
	
	$opts['kwcex'] = $units;
	$opts['httponly'] = false;
	
	kwas(is_numeric($units), 'one last check of units failed location 0240');
	kwas(locSessCl::validLLSS($fa['cookieValue']), 'bad location value string 0224');
	
	$nv = locSessCl::getJSON($fa['cookieValue']);
	if ($nv) kwscookie($cname, $nv, $opts);
}

location_cc();
