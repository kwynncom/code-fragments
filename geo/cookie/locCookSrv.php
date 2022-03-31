<?php

require_once('/opt/kwynn/kwutils.php');
require_once('location.php');

class locCookieCl {
	
	const cname = 'location';
	
	public function __construct() {
		$this->receive();
		$this->sendExp();
	}

private function send20($exs) {
	$ret['exists'] = true;
	$ret['locss'] = $this->locss;
	if (!$exs) kwjae(kwam($ret, ['exp' => false]));
	$ret['exp'] = true;
	$ret['raw'] = $exs;
	$sec = $ret['tss'] = strtotime($exs);
	$ret['tsms'] = $sec * 1000;
	kwjae($ret);
}
	
private function sendExp() {
	$ha = headers_list(); // indexed 0, 1, ...
	foreach($ha as $r) {
		if (strpos($r, 'Set-Cookie: ' . self::cname) !== 0) continue;
		preg_match('/expires=([^;]+)/', $r, $ms);
		return $this->send20(kwifs($ms, 1));
	}
	
	kwjae(['exists' => false]);
}
	
private function receive() {

	$fa = kwjssrp();
	
	if (kwifs($fa, 'cookieAction') !== 'setExpiration') return;
	
	switch($fa['unit']) {
		case 'now'     : $units = -M_MILLION * 4; break;
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
	
	$this->locss = $fa['cookieValue'];
	
	$nv = locSessCl::getJSON($fa['cookieValue']);
	if ($nv) kwscookie(self::cname, $nv, $units);
}
}

if (didAnyCallMe(__FILE__)) new locCookieCl();
