<?php

require_once('/opt/kwynn/kwutils.php');

class kwBackupSome {
	
	public function __construct() {
		$this->base10();
		$this->do10();
		pclose($this->oh);
	}
	
	private function base10() {
		$this->obase = trim(shell_exec('echo ~')) . '/';
		echo($this->obase . "\n");
		$this->oh = popen('find ~ -maxdepth 1 ', 'r');
	}

	private function dobranch($pin) {
		static $ps = [];
		static $ls = [];
		static $b = ['.bash_history', '.aws', '.ssh', '.bashrc' ];
		
		if (!$ps) foreach($b as $p) {
			$s = $this->obase . $p . '';
			$ps[] = $s;
			$ls[] = strlen($s);
		}
		
		kwynn();
		
		foreach($ps as $i => $p) {
			$t10 = substr($pin, 0, $ls[$i]);
			if ($t10 === $ps[$i]) return true;
		}
		return false;
		
		
	}
	
	private function do10() {

		while($s = fgets($this->oh)) {
			if ($this->dobranch($s)) echo($s);
			// echo($s);
		}

	}

}

new kwBackupSome(); // .bash_history is special
