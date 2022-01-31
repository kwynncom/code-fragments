<?php

require_once('/opt/kwynn/kwutils.php');

class moon { 
	
	const tfile = '/tmp/kwmoonv2022011.json';
	
	public function __construct() {
		$a = $this->do10();
		return;
		
	}
	
	function do10() {
		if (is_readable(self::tfile)) return json_decode(file_get_contents(self::tfile), 1);
		return $this->do20(trim(shell_exec('python3 ' . __DIR__ . '/moon.py')));
	}
	
	function do20($t) {
		$aa = explode("\n", $t); unset($t);
		kwas(count($aa) === 3, 'moon bad count 0020');
		$ms = [];
		foreach([0,1] as $i) preg_match_all("/'([^']+)'/", $aa[$i], $ms[$i]); unset($i);
		$a['z'] = $ms[0][1];
		$a['t'] = $ms[1][1];
		preg_match_all('/\d/', $aa[2], $ms[2]); unset($aa);
		$a['n'] = $ms[2][0]; unset($ms);
		$r = [];
		foreach($a['z'] as $i => $p) {
			$ts = strtotime($p);
			$r[$p] = [intval($a['n'][$i]), $a['t'][$i], $ts, date('r', $ts)]; unset($ts);
		} 	unset($i, $p, $a);
		
		file_put_contents(self::tfile, json_encode($r));
		return $r;
		
	}
	
	public static function ppyarr($s) {
		return str_replace("'", '"', $s) . ';';
		
	}
}

new moon();