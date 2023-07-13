<?php

require_once('/opt/kwynn/kwutils.php');
require_once('loginCheck.php');
require_once(__DIR__ . '/../' . 'config.php');

class tmate_logs_show implements tmate_config {
	
	const tpfx = __DIR__ . '/template/';
	
	private string $user;
	
	
	public function __construct() {
		$this->setDir();
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
	
	private function setDir() {
		$this->odir = self::sessdir;
	}
	
	private function do20(string $f) {
		kwas(preg_match('/^[A-Za-z0-9_\-\.]+$/', $f), 'bad param format - 2226');
		kwas(strlen($f) <= self::maxfnstrlen, 'bad param format - 2228');
		kwas(strpos($f, '..') === false, 'bad param format - 2230');
		
		$p = $this->odir . $f;
		kwas(is_readable($p), 'bad p format - 2230-2');
		$t = file_get_contents($p);
		$safet = htmlspecialchars($t); unset($t);
		$this->do25($safet);
	}
	
	private function do25(string $t) {
		require_once(self::tpfx . 't50.php');
	}
	
	
	private function do40() {
		$fs = shell_exec('ls -t ' . $this->odir);
		if (!$fs || !is_string($fs)) return;
		$fs = trim($fs);
		$a  = preg_split('/\s+/', $fs);
		$this->do50($a);
		return;		
	}
	
	
	private function getDirHT() : string {
		$t = '';
		$t .= '<p>';
		if ($this->odir === self::sessdir) $t = 'raw (no geo IP)';
		$t .= '</p>' . "\n";
		return $t;
	}
	
	
	private function getGeo(string $pre, string $f) : array {
		$p = self::byodir . $f;
		if (!is_readable($p)) {
			$p = $this->getGeoNmByHash($pre, $f);
			if (!is_readable($p)) return [];
		}
		
		$j = trim(file_get_contents($p));
		$l = strlen($j);
		$a = json_decode($j, true);
		if (!$a) return [];
		return $a;
	}
	
	private function getGeoNmByHash(string $pre, string $f) : string {
		$hash = tmate_get_hash(file_get_contents($pre . $f));
		$p = self::hashdir . $hash;
		if (is_readable($p)) return $p;
		return '';
	}
	
	private function do50($a) {
		// echo($this->getDirHT());
		
		echo('<table>' . "\n");
		foreach($a as $f) {
			$fo = $this->odir . $f;
			$ts  = filemtime($fo);
			$geo = $this->getGeo($this->odir, $f);
			$hu = tmate_get_fn('', $ts, $geo);
			$url = '?f=' . $f;
			require(self::tpfx . 't30.php');
		}
		echo('</table>' . "\n");
		

	}
}

new tmate_logs_show();