<?php

require_once('/opt/kwynn/kwutils.php');

class kwBackupSome {
	
	// need to track stderr, I think
	// Using the update option (-u) with cp should do it for you. cp if later
	// -a archive - preserve all
	
	const cbasecmd =  'find ~ -maxdepth 1';
	// const cbasecmd =  'find ~/tech/frag_backup';
	const stopat = 100000;
	

	private function doCopy(string $p) {		
		static $bsz = false;
		static $i = 0;
		if (!$bsz) $bsz = strlen($this->obasep);
		
		// $p = str_replace('$', '\$', $p); // this is for rsync / the shell, not PHP
		
		$tp = $this->owrd . '' . substr($p, $bsz);
		$c  = 'rsync -aL4zvv --mkpath ';
		$c .= ' "' . $p . '" ';
		$c .= ' ';
		$c .= ' "' . $tp . '" ';
		echo($c . "\n");

		if (1) {
			// shell_exec(escapeshellcmd($c));
			shell_exec($c);
			kwas(file_exists($tp), 'copy failed 00:21 kwbk');
		}
		if (++$i > self::stopat) { echo('file limit reached - perhaps testing?  00:12'); $this->gracefulExit();  }
		

		
		
		
	}
	
	private function gracefulExit() {
		if (!($h = kwifs($this, 'oh'))) return;
		pclose($h);
		exit(0);
	}
	
	private function init05() {
		global $argc;
		global $argv;
		
		if ($argc < 2) return;
		$p = $argv[1];
		kwas(is_writable($p), "$p not writeable");
		$this->owrd = $p;
	}
	
	public function __construct() {
		$this->osz = 0;
		$this->init05();
		$this->base10();
		$this->init10();
		$this->do10();
		$this->sum10();
	}
	
	
	private function sum10() {
		echo(number_format($this->osz) . "\n");
	}
	
	private function base10() {
		$this->obasep = trim(shell_exec('echo ~'));
	}

	private function testBranch($pin) : bool {
		
		foreach($this->ops as $act => $a) {
			foreach($a['ps'] as $i => $ignoreV) {
				$t10 = substr($pin, 0, $a['ls'][$i]);
				if ($t10 === $a['ps'][$i]) return $act ? true : false;
			}
		}
		
		return true;
	}
	
	private function init10() {
		$this->setPaths();
	}
	
	private function setPaths() {
		
		$ps = [];
		$ps[true] = ['.bash_history', '.aws', '.ssh', '.bashrc' ];
		$ps[false] = ['.', 'snap', 'arch'];
		$doo = [];
		
		$this->ops = [];
				
		foreach([true, false] as $act) {
			$doo[$act]['ps'] = [];
			$doo[$act]['ls'] = [];
			$this->popPaths($ps[$act], $doo[$act]['ps'], $doo[$act]['ls']);
			$this->ops[$act] = $doo[$act];
		}
	}
	
	private function popPaths(array $b, array &$ps, array &$ls) {
		if (!$ps) foreach($b as $p) {
			$s = $this->obasep . '/' . $p . '';
			$ps[] = $s;
			$ls[] = strlen($s);
		}		
		
	}
	
	private function do10() {
		
		$ab = explode("\n", shell_exec(self::cbasecmd));

		foreach($ab as $s) {
			if ($s === $this->obasep) continue;
			if (!trim($s)) continue;
			if (!$this->testBranch($s)) continue;
			$this->doBranch($s);
		}

	}
	
	private function skipDir($p) {
		static $ss = ["(/\.git)", "(/node_modules)", "(/AWS/logs)"];
		foreach($ss as $t) if (preg_match($t, $p)) return true;
		return false;
		
	}
	
	private function doBranch($pin) {
		$cmd  = "find \"$pin\" -type f -printf '%s %p" . '\n' . "' 2> /dev/null";
		// echo($cmd . "\n");
		$this->oh = $h = popen($cmd, 'r');		
		while ($s = trim(fgets($h))) {
			if ($this->skipDir($s)) continue;
			echo($s . "\n");
			preg_match('/^(\d+)\s+(.*)/', $s, $ms);
			$this->osz += intval($ms[1]);
			// $this->doCopy($ms[2]); // *************
		}
		
		pclose($h);
		$this->oh = false;
		

	}

}

new kwBackupSome(); // .bash_history is special ; assumes no files as direct children of ~
