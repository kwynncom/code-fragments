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
		$a  = explode(' ', $fs);
		return;
	}
}

new tmate_logs_show();