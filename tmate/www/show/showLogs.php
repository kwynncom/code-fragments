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
		
		require_once(self::tpfx . 't10_head.php');
		
		$f = isrv('f');
		if (!$f) $this->do40();
		else     $this->do20($f);
		
		require(self::tpfx . 't90_footer.php');

	}
	
	private function do20(string $f) {
		kwas(preg_match('/^[A-Za-z0-9_\-\.]+$/', $f), 'bad param format - 2226');
		kwas(strlen($f) <= self::maxfnstrlen, 'bad param format - 2228');
		kwas(strpos($f, '..') === false, 'bad param format - 2230');
		$p = self::sessdir . $f;
		kwas(is_readable($p), 'bad p format - 2230-2');
		$t = file_get_contents($p);
		$safet = htmlspecialchars($t); unset($t);
		$this->do25($safet);
	}
	
	private function do25(string $t) {
		require_once(self::tpfx . 't50.php');
	}
	
	
	private function do40() {
		$fs = shell_exec('ls -t ' . tmate_config::sessdir);
		if (!$fs || !is_string($fs)) kwas(false, 'could not read tmate log dir');
		$a  = preg_split('/\s+/', $fs);
		$this->do50($a);
		return;		
	}
	
	
	
	private function do50($a) {
		
		echo('<table>' . "\n");
		foreach($a as $f) {
			$ts = filemtime(self::sessdir . $f);
			$hu = date('Y-m-d h:i', $ts);
			$url = '?f=' . $f;
			require(self::tpfx . 't30.php');
		}
		echo('</table>' . "\n");
		

	}
}

new tmate_logs_show();