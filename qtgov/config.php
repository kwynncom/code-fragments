<?php

require_once('/opt/kwynn/kwutils.php');

class callSNTP {

	const simShell = false;
	
	const locPath =  './../../ntpc/';
	const file    = 'wrap.php';
	const cmd     = 'php';
	const rescnt  = 4;
	const mustBeAfter = 1634878511762238252;
	
	private function __construct($ip = '34.193.238.16') {
		$this->ip = $ip;
		$this->init();
		$this->doit();
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
		if (!self::simShell) $r = shell_exec($cmd);
		else $r = '[1634880687753702139,1634880687797246864,1634880687797327204,1634880687815166145]';
		$a = json_decode(trim($r));
		$this->setValid($a);
	}
	
	private function setValid($a) {
		if (!is_array($a)) return;
		if (count($a) !== self::rescnt) return;
		for($i=0; $i <    self::rescnt; $i++) {
			if (!is_integer($a[$i])) return;
			if ($a[$i] < self::mustBeAfter) return;
		}
		
		if ($i !== self::rescnt) return;
		$this->ores = $a;
		return;
	}
	
	public function getRes() { return $this->ores; }
	
	public static function get() {
		$o = new self();
		return $o->getRes();
	}

}
if (didCLICallMe(__FILE__)) print_r(callSNTP::get());
