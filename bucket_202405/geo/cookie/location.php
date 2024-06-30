<?php

require_once('/opt/kwynn/kwutils.php');

class locSessCl {

	
	public static function getArrFromCookie() {
		
		static $errr = false;
		
		if (!isset ($_COOKIE)) return $errr ;
		$json = kwifs($_COOKIE, 'location');
		if (!$json) return $errr;
		$a = json_decode($json, 1);
		if (!isset($a['ss'])) return $errr;
		if (!($a = self::validLLSS($a['ss']))) return $errr;
		return $a;
		
	}
	
	public static function getVSS() {
		$ret = '';
		try {
			$o = new self();
			$ret = $o->getVSSI(); } catch (Exception $ex) { }
		return $ret;
	}
	
	public static function getJSON($prepop = false) {
		$ret = false;
		try {
			$o = new self($prepop);
			$ret = $o->getJSONI(); } catch (Exception $ex) { }
		return $ret;		
	}

	public function getJSONI() {
		$ret = kwifs($this->theva, 'json'); 
		kwas($ret && is_string($ret) && strlen(trim($ret)) > 8, 'invalid getSS()');	
		return $ret;
	}
	
	private function getVSSI() { 
		$ret = kwifs($this->theva, 'ss'); 
		kwas($ret && is_string($ret) && strlen(trim($ret)) > 2, 'invalid getSS()');
		return $ret;
	}
	
	public function __construct($prepop = false) {
		$this->theva = false;
		$this->do10($prepop);
	}
	
	private function do10($prepop) {
		if ($prepop) $popwith = $prepop;
		else	     $popwith = isrv('latlonssForm');
		$this->theva = self::validLLSS($popwith);
	}
	
	public static function validLLSS($sin) {
		
		try {

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

			$jsa['ia'] = $va;
			$jsa['ss'] = $sin;
			$jsa = kwam($jsa, ['lat' => $va[0], 'lon' => $va[1]]);
			$ret['json'] = json_encode($jsa);
			$ret = kwam($ret, $jsa);
			
			return $ret;
		} catch(Exception $ex) { }
		
		return FALSE;
	}
}
