<?php

require_once('/opt/kwynn/kwutils.php');

class kwBackupSome {
	
	const cbasecmd =  'find ~ -maxdepth 1';
	
	public function __construct() {
		$this->base10();
		$this->init10();
		$this->do10();
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
			break; // TEST *******
		}

	}
	
	private function doBranch(string $br) {
		$h = popen("find $br -type f ", 'r');
		while($s = fgets($h)) {
			if (preg_match('/\/\.git\//', $s)) continue;
			echo($s);
		}
		
		
	}

}

new kwBackupSome(); // .bash_history is special ; assumes no files as direct children of ~
