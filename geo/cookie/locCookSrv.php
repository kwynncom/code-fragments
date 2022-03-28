<?php

require_once('/opt/kwynn/kwutils.php');
require_once('location.php');

class locCookieCl {
	
	const cname = 'location';
	
	public function __construct() {
		self::receive();
		self::sendExp();
	}

private static function sendExp() {
	if (!isset($_COOKIE)) return '';
	$a = kwifs($_COOKIE, self::cname);
	if (!$a) kwjae(['exists' => false]); unset($a);
	$ha = headers_list(); // indexed 0, 1, ...
	// Set-Cookie: location
	// expires=Mon, 28-Mar-2022 03:14:11 GMT; 
	
	
	return;
}
	
private static function receive() {

	$fa = kwjssrp();
	
	if (kwifs($fa, 'cookieAction') !== 'setExpiration') return;
	
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
		default : kwas(false, 'invalid unit sent location 0238'); break;
		
	}
	
	kwas(is_numeric($units), 'one last check of units failed location 0240');
	kwas(locSessCl::validLLSS($fa['cookieValue']), 'bad location value string 0224');
	
	$nv = locSessCl::getJSON($fa['cookieValue']);
	if ($nv) kwscookie(self::cname, $nv, $units);
}
}

if (didAnyCallMe(__FILE__)) new locCookieCl();
