<?php

require_once('/opt/kwynn/kwutils.php');

class kw_shell_exec_cl {
	
	const bpath = '/tmp/';
	const dexec = 'iping';
	
	private function __construct($c) {
		$this->exres = '';
		$this->exCmd = $c;
		$this->exres = $this->do10();
	}
	
	private function do10() {
		$c = $this->exCmd;
		$n = nanopk();
		// $f = self::bpath . $n['tsc'] . '_' . $n['pid'];
		$f = '/dev/null';
		$pc = $c . ' > ' . $f . ' 2>&1 & echo $! ';
		$pid = trim(shell_exec($pc));
		
		$tc = "tail -f --pid=$pid /dev/null";
		echo($tc);
		shell_exec($tc);
		
		// return file_get_contents($f);

	}
		
	
	
	public function getRes() { return $this->exres; }
	
	public static function doit($c) {
		if (!$c) $c = self::dexec;
		if (!isxon() && 0) return shell_exec($c);
		$o = new self($c);
		return $o->getRes();
	}
}

function kw_shell_exec($cmd = '') { return kw_shell_exec_cl::doit($cmd); }

function isxon() { 
	$f = 'xdebug_is_debugger_active';
	return function_exists($f) && $f();
} 

if (didCLICallMe(__FILE__)) echo(kw_shell_exec());

