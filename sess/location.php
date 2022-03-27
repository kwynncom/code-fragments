<?php

require_once('/opt/kwynn/kwutils.php');

class locSessCl {
	
	public static function getVSS() {
		$ret = '';
		try {
			$o = new self();
			$ret = $o->getSS(); } catch (Exception $ex) { }
		return $ret;
	}
	
	public function getSS() { 
		$ret = kwifs($this->theva, 'ss'); 
		kwas($ret && is_string($ret) && strlen(trim($ret)) > 2, 'invalid getSS()');
		return $ret;
	}
	
	public function __construct() {
		$this->theva = false;
		$this->do10();
	}
	
	private function do10() {
		$this->theva = self::validLLSS(isrv('latlonssForm'));
	}
	
	public static function validLLSS($sin) {

		kwas($sin && is_string($sin), 'no valid location info 2105');
		$l = strlen($sin); kwas($l > 0 && $l < 30, 'no valid location info 3 2107'); unset($l);
		kwas(preg_match_all('/[\d\.\-]+/', $sin, $ms), 'no valid loc info 2 2106');
		kwas(isset($ms[0][1]), 'no valid loc 4 2110');
		
		$sa = $ms[0]; unset($ms);
		
		$va = [];
		foreach($sa as $i => $s) {
			kwas(is_numeric($s), 'bad loc info 5 2113');
			$fl = floatval($s);
			$absfl = abs($fl);
			kwas($absfl < 180.001, 'bad loc 6 2115');
			if ($i === 0) kwas($absfl < 90.001, 'bad loc 7 2115');
			$va[$i] = $fl;
		}
				
		return ['ss' => $sin, 'lat' => $va[0], 'lon' => $va[1]];
	}
}

// if (didAnyCallMe(__FILE__)) new locSessCl();