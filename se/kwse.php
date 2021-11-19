<?php

require_once('/opt/kwynn/kwutils.php');

class kw_shell_exec_cl {
	
	const bpath = '/tmp/';
	const dexec = 'iping';
	
	const fbase = '/tmp/kwex_';
	
	private function __construct($c) {
		$this->exres = '';
		$this->exCmd = $c;
		$this->exres = $this->do10();
	}
	
	private function do10() {
		
		$this->setFiles();
		exit(0); // ****
		
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
		
	private function setFiles() {
		$this->setFileBase();
		$this->sf20();
	}
	
	private function sf20() {
		$fs = ['ctos', 'stoc'];
		foreach($fs as $fsfx) {
			$f = $this->fbase . $fsfx;
			$this->{'f' . $fsfx} = $f;
			if (file_exists($f)) { self::vf30orDie($f); continue; }
			posix_mkfifo($f, 0600);
		}
	}
	
	private static function vf30orDie($f) {
		kwas(filetype($f) === 'fifo', 'bad file type not fifo kwexec');
		kwas(substr(sprintf('%o', fileperms($f)), -4) === '0600', 'bad perms kwexec');
	}
	
	private function setFileBase() {
		$b10 = preg_replace('/[^A-Za-z_0-9]/', '_', $this->exCmd);
		$b20 = substr($b10, 0, 15);
		$b30 = self::fbase . get_current_user() . '_' . $b20 . '_';
		$this->fbase = $b30;
	}

	
	public function getRes() { return $this->exres; }
	
	public static function doit($c) {
		if (!$c) $c = self::dexec;
		if (!self::isxon()) return shell_exec($c);
		$o = new self($c);
		return $o->getRes();
	}
	
	public static function isxon() { $f = 'xdebug_is_debugger_active'; return function_exists($f) && $f(); } 

}

function kw_shell_exec($cmd = '') { return kw_shell_exec_cl::doit($cmd); }


if (didCLICallMe(__FILE__)) echo(kw_shell_exec('php -v'));

