<?php

require_once('/opt/kwynn/kwutils.php');
require_once('loginCheck.php');
require_once(__DIR__ . '/../' . 'config.php');

class tmate_logs_show implements tmate_config {
	
	const tpfx = __DIR__ . '/template/';
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		if (PHP_SAPI !== 'cli') $user = getHTLIUserOrDie();
		else $user = 'cli';
		
		$this->user = $user;
		$this->do15();

	}
	
	private function do15() {
		$fs = shell_exec('ls -t ' . tmate_config::sessdir);
		if (!$fs || !is_string($fs)) kwas(false, 'could not read tmate log dir');
		$a  = preg_split('/\s+/', $fs);
		$this->do20($a);
		return;		
	}
	
	
	private function do20(array $a) {
		
		require_once(self::tpfx . 't10_head.php');
		
		$this->do30($a);
		
		require_once(self::tpfx . 't90_footer.php');
	}
	
	private function do30($a) {
		foreach($a as $f) {
			$ts = filemtime(self::sessdir . $f);
			$hu = date('Y-m-d h:i', $ts);
			$url = '?f=' . $f;
			require(self::tpfx . 't30.php');		
		}
		

	}
}

new tmate_logs_show();