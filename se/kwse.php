<?php

require_once('/opt/kwynn/kwutils.php');

class kw_shell_exec_cl {
	
	const bpath = '/tmp/';
	const dexec = 'iping';
	
	private function __construct($c) {
		$this->exCmd = $c;
		$this->do10();
	}
	
	private function do10() {
		
		global $argv;
		global $argc;
		
		$c = $this->exCmd;
		$h = $this->exCmdH = md5($c);
		// $c = 'php ' . __DIR__ . '/sea.php ' . $h;
		
		$f05 =  self::bpath . $h; 
		$fres = $f05 . '_res.txt';
		$f10 = $f05 . '_cmd.txt';
		$f15 = $f05 . '_lock';
		file_put_contents($f15, '');
		$finn = $f05 . '_inn';
		$fout = $f05 . '_out';
		foreach([$finn, $fout] as $f) {
			if (!file_exists($f)) posix_mkfifo($f, 0600);
		}
		
		if ($argc < 2 || $argv[1] !== 'exec') {
			$lo  = new sem_lock($f15);
			$lo->lock();
			file_put_contents($f10, $c);
			$this->exres = shell_exec(__FILE__ . ' exec ' . $h . ' > /dev/null 2>&1 &');
			$rinn = fopen($finn, 'w+'); if (!$rinn) die('open fail');
			if (!fwrite($rinn, 'x', 1)) die('write fail');
			echo(file_get_contents($fout));
			fclose($rinn);
			$lo->unlock();
			unset($lo);
		} else {
			$c = file_get_contents($f10);
		
			while(1) {
				$rinn = fopen($finn, 'r');
				if (!$rinn) die('rinn open fail');
				$t = fread($rinn, 1);
				$l = strlen($t);
				$us = microtime(1);
				echo('read' . " $l $us\n");
				fclose($rinn);
				if (!$l) continue;
				$sout = shell_exec($c . ' > ' . $fout . ' &');
		
				exit(0);
			}
			

		}
		
		return file_get_contents($f20);
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

