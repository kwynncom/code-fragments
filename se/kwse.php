<?php

require_once('/opt/kwynn/kwutils.php');

class kw_shell_exec_cl {
	
	const bpath = '/tmp/';
	const dexec = 'iping';
	
	const fbase = '/tmp/kwex_';
	
	private function __construct($c, $iamsrv) {
		$this->iamsrv = $iamsrv;
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
		if ($this->iamsrv) $this->start10(); // server
	}
	
	private function start10() {
		while (1) {
			$h = fopen($this->fctos, 'r');
			$t = trim(fgets($h));
			fclose($h);
			if (!$t) continue;
			$r = shell_exec($t);
			$oh = fopen($this->fstoc, 'w+');
			fwrite($oh, $r, strlen($r));
			fclose($oh);
		}		
	}
	
	private function sf20() {
		$this->locko = new sem_lock(__FILE__);
		$this->locko->lock();
		$fs = ['ctos', 'stoc'];
		foreach($fs as $fsfx) {
			$f = $this->fbase . $fsfx;
			$this->{'f' . $fsfx} = $f;
			if (file_exists($f)) { self::vf30orDie($f); continue; }
			posix_mkfifo($f, 0600);
		}
		$this->locko->unlock();
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
	
	private static function issrv() {
		global $argc;
		global $argv;
		
		if ($argc < 2) return false;
		if ($argv[1] === 'ss') return true;
		return false;
	}
	
	public static function doit($c = '') {
		$iss = self::issrv();
		if (!$iss) {
			if (!$c) $c = self::dexec;
			if (!self::isxon()) return shell_exec($c);
		}
		$o = new self($c, $iss);
		return $o->getRes();
	}
	
	public static function isxon() { $f = 'xdebug_is_debugger_active'; return function_exists($f) && $f(); } 

}

function kw_shell_exec($cmd = '') { return kw_shell_exec_cl::doit($cmd); }


if (didCLICallMe(__FILE__)) echo(kw_shell_exec('php -v'));

