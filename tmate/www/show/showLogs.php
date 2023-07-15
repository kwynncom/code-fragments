<?php

require_once('/opt/kwynn/kwutils.php');
require_once('loginCheck.php');
require_once(__DIR__ . '/../' . 'config.php');

class tmate_logs_show implements tmate_config {
	
	const tpfx = __DIR__ . '/template/';
	const nll = 32; // to fix newline issue - strlen of "Sat Jul 15 01:30:16 2023 [tmate]"
	const needle = '[tmate]';
	const tml =  7; // stelen of "[tmate]"
	const tosub = self::nll - self::tml;
	const maxtmate = 20;
	
	private string $user;
	
	
	public function __construct() {
		$this->fga = [];
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
		
		$b = $this->odir;
		
		foreach([self::alldbyof, self::sessdir] as $dir) {
			$p =  $dir . $f;
			if (!is_readable($p)) continue;
			$t = file_get_contents($p);
			$safet = htmlspecialchars($t); unset($t);
			break;
		}
		$this->do25($safet);
	}
	
	private function do25(string $t) {
		$t = $this->fixNewline($t);
		require_once(self::tpfx . 't50.php');
	}
	
	private function fixNewline(string $tin) : string {

		$t = trim($tin); unset($tin);

		$i = $pos = 0;

		while ($i++ < self::maxtmate) { // sanity check; prevent infinite loop
			$pos = strpos($t, self::needle, $pos);
			if ($pos === false) break;
			if ($i > 1) $t = substr_replace($t, "\n", $pos - self::tosub, 0); // 0 is "magic" as defined in the PHP function
			$pos += self::tml + 1; // + 1 for the newline added; I don't think it matters, but I'm leaving it.
		}
		
		return $t;
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
	
	
	private function getGeoPath(string $pre, string $f, int $U) : array {
		
		$mp = self::metap . $f;
		if (is_readable($mp)) return json_decode(trim(file_get_contents($mp)), true); unset($mp);
						
		$defr = ['hu' => tmate_get_fn('', $U)];
		
		$p = self::hashdir . $f;
		if (!is_readable($p)) {
			$p = $this->getGeoNmByHash($pre, $f);
			if (!is_readable($p)) return $defr;
		} 
		
		$j = trim(file_get_contents($p));
		$l = strlen($j);
		$a = json_decode($j, true);
		if (!$a) return $defr;
		
		$this->saveGeoByOF($U, $a, $f);
	
		return tmate_get_fn('', $U, $a, true);
	}
	
	private function saveGeoByOF(int $U, array $geo, string $fin) {
		$j = json_encode($geo, JSON_PRETTY_PRINT);
		$path = tmate_config::alldbyof . $fin;
		$tm = file_get_contents(self::sessdir . $fin);
		$ta = $tm . $j;
		mkdir_safe(tmate_config::alldbyof);
		kwtouch   ($path, $ta, self::permf);
		$a = tmate_get_fn('', $U, $geo, $fin, true);
		$j = json_encode($a, JSON_PRETTY_PRINT);
		$mf = self::metap . $fin;
		mkdir_safe(self::metap);
		kwtouch   ($mf, $j . "\n", self::permf);
			
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
			$pa = $this->getGeoPath($this->odir, $f, $ts);
			$hu = $pa['hu'];
			$url = '?f=' . $f;

			require(self::tpfx . 't30.php');
		}
		echo('</table>' . "\n");
		

	}
}

new tmate_logs_show();