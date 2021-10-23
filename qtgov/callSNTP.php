<?php

require_once('/opt/kwynn/kwutils.php');
require_once('config.php');

class callSNTP extends callSNTPConfig {

	const simShell = 0;
	const rescnt   = 4;
	
	private function simShell() {
		if (isAWS()) return false;
		if (!self::simShell) return false;
		$t = $r[0] = nanotime();
		$r[1] = $t + intval(round(self::toleranceNS * 0.999));
		$r[2] = $r[1];
		$r[3] = $t;
		return json_encode($r);
	}
	
	private function __construct($ip = self::defaultIP) {
		$this->ip = $ip;
		$this->tt = nanotime();
		$this->init();
		$this->doit();
		$this->calcs();
	}

	private function calcs() {
		if (!isset($this->ores['raw'])) return;
		$or  =	   $this->ores;
		$a   =	$or['raw'];
		$min = $or['min'] = min($a);
		for($i=0; $i < 2; $i++) $or['relmss'][$i] = self::fms($a[$i] - $min);
		$avgns = (($a[3] + $a[0]) >> 1);
		$avgs = self::fms($avgns - $min);
		$or['relmss'][2] = $avgs;
		for($i=2; $i <= 3; $i++) $or['relmss'][$i + 1] = self::fms($a[$i] - $min);
		$avgsns = ($a[2] + $a[1]) >> 1;
		$d = $avgns - $avgsns;
		$or['d'] = $d / M_MILLION;
		$or['out'] = self::fms($a[1] - $a[0]);
		$or['in']  = self::fms($a[3] - $a[2]);
		$this->ores = $or;
	}
	
	public static function fms($ns) {
		$mss = sprintf('%0.4f', $ns / M_MILLION);
		$r   = sprintf('%8s', $mss);
		return $r;
				
	}
	
	private function init() {
		$this->ores = false;
		if (!isAWS()) $pp = self::locPath;
		else kwas(false, 'not set for AWS / live yet');	
		$p = $pp . self::file;
		kwas(is_readable($p));
		$this->path = $p;
		
	}
	
	private function doit() {
		$cmd = trim(self::cmd . ' ' . $this->path . ' ' . $this->ip);
		if (!($r = $this->simShell())) $r = shell_exec($cmd);
		$a = json_decode(trim($r));
		$this->setValid($a);
	}
	
	private function setValid($a) {
		if (!is_array($a)) return;
		if (count($a) !== self::rescnt) return;
		for($i=0; $i <    self::rescnt; $i++) {
			if (!is_integer($a[$i])) return;
			$d = abs($a[$i] - $this->tt);
			if ($d > self::toleranceNS) return;
		}
		
		if ($i !== self::rescnt) return;
		$this->ores['raw'] = $a;
		return;
	}
	
	public function getRes() { return $this->ores; }
	
	public static function get() {
		$o = new self();
		return $o->getRes();
	}

}
if (didCLICallMe(__FILE__)) print_r(callSNTP::get());
