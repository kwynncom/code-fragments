<?php

require_once('/opt/kwynn/kwutils.php');

class kwBackupSome {
	
	public function __construct() {
		$this->base10();
		$this->init10();
		$this->do10();
		pclose($this->oh);
	}
	
	private function base10() {
		$this->obase = trim(shell_exec('echo ~')) . '/';
		$this->oh = popen('find ~ -maxdepth 1 ', 'r');
	}

	private function dobranch($pin) {
		static $ps = [];
		static $ls = [];
		static $b  = ['.bash_history', '.aws', '.ssh', '.bashrc' ];
		
		if (!$ps) $this->popPaths($b, $ps, $ls);
		
		foreach($ps as $i => $p) {
			$t10 = substr($pin, 0, $ls[$i]);
			if ($t10 === $ps[$i]) return true;
		}
		return false;
		
		
	}
	
	private function init10() {
		$this->setPaths();
	}
	
	private function setPaths() {
		
		$ps = [];
		$ps[true] = ['.bash_history', '.aws', '.ssh', '.bashrc' ];
		$ps[false] = ['.', 'snap', 'arch'];
		$doo = [];
				
		foreach([true, false] as $act) {
		
			$doo[$act]['ps'] = [];
			$doo[$act]['ls'] = [];
			$this->popPaths($ps[$act], $doo[$act]['ps'], $doo[$act]['ls']);
		}
		
		return;
		
	}
	
	private function popPaths(array $b, array &$ps, array &$ls) {
		if (!$ps) foreach($b as $p) {
			$s = $this->obase . $p . '';
			$ps[] = $s;
			$ls[] = strlen($s);
		}		
		
	}
	
	private function nobranch() {
		static $ps = [];
		static $ls = [];
		static $p  = ['.', 'snap', 'arch'];
		

	}
	
	private function do10() {

		while($s = fgets($this->oh)) {
			if ($this->dobranch($s)) { echo($s); continue; }
			
			// echo($s);
		}

	}

}

new kwBackupSome(); // .bash_history is special
