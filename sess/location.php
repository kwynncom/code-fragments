<?php

require_once('/opt/kwynn/kwutils.php');

class locSessCl {
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		$v = isrv('latlonssForm');
		kwas($v && is_string($v), 'no valid location info 2105');
		$l = strlen($v); kwas($l > 0 && $l < 30, 'no valid location info 3 2107'); unset($l);
		kwas(preg_match_all('/[\d\.\-]+/', $v, $ms), 'no valid loc info 2 2106');  unset($v);
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
		
		
		
		return;
	}
}

// if (didAnyCallMe(__FILE__)) new locSessCl();