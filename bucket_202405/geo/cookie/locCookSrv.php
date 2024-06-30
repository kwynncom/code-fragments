<?php

require_once('/opt/kwynn/kwutils.php');
require_once('location.php');

class locCookieCl {
	
	const cname = 'location';
	
	public function __construct() {
		self::processExpDets();
		self::getDets();
	}

	private static function retOrExit($dat) {
		if (didAnyCallMe(__FILE__)) kwjae($dat);
		return json_encode($dat);
		
	}
	
private static function sendPreEx() {
	$a = locSessCl::getArrFromCookie();
	if (!$a) self::retOrExit(['exists' => false]);
	$ret['exists'] = true;
	$ret['locss' ] = $a['ss'];
}

public static function getSSFromRC($rexs, $rs) {
	
	$k10 = self::cname . '=';
	$p05 = strpos($rs, $k10);
	$p10 = strpos($rs, $rexs);
	$pfl = strlen($k10);
	$rv  = substr($rs, $p05 + $pfl, $p10 - $pfl - $p05);
	$rv20 = str_replace(';', '', $rv);
	$uds = urldecode($rv20);
	$a = json_decode($uds, true);
	return $a['ss'];
}

private static function send20($exs, $preEx = false, $newrc = false, $rexs = '') {
	
	$a = locSessCl::getArrFromCookie();
	if (isset($a['ss']) && $preEx) $ret['locss'] = $a['ss'];
	if (!$exs && !$preEx) self::retOrExit(kwam(['exists' => true], ['exp' => false]));

	$ret['exists'] = true;
	
	if ($newrc) $ret['ss'] = self::getSSFromRC($rexs, $newrc);
	
	if ($exs !== 'unk') {
		$ret['exp'] = true;
		$ret['raw'] = $exs;
		$sec = $ret['tss'] = strtotime($exs);
		$ret['tsms'] = $sec * 1000;
	} else $ret['exp'] = 'uknown because pre-existing';
	
	return self::retOrExit($ret);
}
	
public static function getDets($preEx = false) {
	
	$ha = headers_list(); // indexed 0, 1, ...
	foreach($ha as $r) {
		if (strpos($r, 'Set-Cookie: ' . self::cname) !== 0) continue;
		preg_match('/expires=([^;]+)/', $r, $ms);
		return self::send20(kwifs($ms, 1), $preEx, $r, kwifs($ms, 0));
	}
	
	if ($preEx) return self::send20('unk', $preEx);
	
	return self::retOrExit(['exists' => false]);
}
	
private function expireNow() {
	$a = locSessCl::getArrFromCookie();	
	if (!$a) return;
	kwscookie(self::cname, false, time() - M_MILLION * 4);
	echo('cookie set to expire now through enf()');
	exit(0);
}

private function processExpDets() {

	$fa = kwjssrp();
	
	if (kwifs($fa, 'expireNow')) return self::expireNow();
	
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
	
	$nv = locSessCl::getJSON($fa['cookieValue']);
	if ($nv) kwscookie(self::cname, $nv, $units);
}
}

if (didAnyCallMe(__FILE__)) new locCookieCl();
