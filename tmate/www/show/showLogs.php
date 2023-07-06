<?php

require_once('/opt/kwynn/kwshortu.php');
require_once('loginCheck.php');
require_once(__DIR__ . '/../' . 'config.php');

class tmate_logs_show {
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		if (PHP_SAPI !== 'cli') $user = getHTLIUserOrDie();
		else $user = 'cli';
		$fs = shell_exec('ls -tr ' . tmate_config::sessdir);
		if (!$fs || !is_string($fs)) kwas(false, 'could not read tmate log dir');
		$a  = preg_split('/\s+/', $fs);
		$this->do20($a);
		return;
	}
	
	private function do20(array $a) {
		return;
	}
}

new tmate_logs_show();