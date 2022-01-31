<?php

require_once('/opt/kwynn/kwutils.php');

class moon extends dao_generic_3 { 
	
	const dbname = 'moon';
	
	public function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs(['m' => 'moon']);
		$this->mcoll->createIndex(['U' => 1], ['unique' => true]);
		$this->do10();
		return;
		
	}
	
	function already() {
		$now = time();
		if (!$this->mcoll->findOne(['U' => ['$lte' => $now - 86400 * 27]])) return false;
		if (!$this->mcoll->findOne(['U' => ['$gte' => $now - 86400 * 45]])) return false;
		return true;
	}
	
	function do10() {
		if ($this->already()) return;
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
		foreach($a['z'] as $i => $z) {
			$U = strtotime($z);
			$n  = intval($a['n'][$i]);
			$t = $a['t'][$i];
			$r = date('r', $U);
			$_id =  $z . '-mph-' . $n;
			// $r[$_id] = $row;
			$dat = get_defined_vars(); 
			unset($dat['a'], $dat['i']);
			kwynn();
			$this->mcoll->upsert(['_id' => $_id], $dat, 1, false); unset($U, $dat, $n, $r, $t, $z, $_id);
		} 	unset($i, $p, $a);
		
		
	}
	
	public static function ppyarr($s) {
		return str_replace("'", '"', $s) . ';';
		
	}
}

new moon();