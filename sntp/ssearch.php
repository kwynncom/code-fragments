<?php

require_once('/opt/kwynn/kwutils.php');

class ntp_server_search {
	
	const pools = ['amazon', 'us', 'ubuntu'];
	// const pools = ['amazon'];
	const maxpooln = 3;
	const keya  = 'Address: ';
	const thresholdrt  = 1.4 * M_MILLION;
	const thresholdoff = 1   * M_MILLION;

	public function __construct() {
		$this->oi = 0;
		$this->do10();
		echo($this->oi . " addresses processed\n");
	}

	private function do30($addr) {
		echo($addr . "\n");
		$r = shell_exec('sntpw -nosleep -ip ' . $addr);
		echo($r);
		if (!strpos($r, '**OK**')) {
			echo("bad result\n");
			return;
		}
		
		$this->do40($r);
		
	}
	
	private function do40($ss) {
		$a = explode("\n", $ss);
		$fs = [0, 3];
		foreach($fs as $i) $a20[$i] = intval(trim($a[$i]));
		$d = $a20[3] - $a20[0]; 
		echo("round trip = " . number_format($d) . ' (' . sprintf('%d', $d / M_MILLION) . 'ms)'. "\n");
		if ($d < self::thresholdrt && self::d($a) < self::thresholdoff) {
			echo("WE HAVE A WINNER!\n");
			exit(0);
		}
		
		$this->oi++;
		
	}
	
	private function do20($t) {
		static $k = self::keya;
		static $l = false;
		
		if (!$l) $l = strlen($k);
		
		$p10 = strpos($t, 'Non-authoritative'); kwas($p10 !== false, 'bad form of nslookup - non-authoritative');
		$t = substr($t, $p10);
		$a = explode("\n", $t);
		foreach($a as $r) if (strpos($r, $k) === 0) $this->do30(trim(substr($r, $l)));
		
		
	}
	
	private function do10() {
		foreach(self::pools as $p) 
			for ($i=0; $i <= self::maxpooln; $i++) {
				$d = $i . '.' . $p . '.pool.ntp.org';
				$c = 'nslookup ' . $d;
				echo($c . "\n");
				$r = shell_exec($c);
				echo($r);
				$this->do20($r);
			}
	}
	
	public static function d($T) { 
		$t = ((($T[1] - $T[0]) + ($T[2] - $T[3]))) >> 1;
		return $t;
	}
	
	
}

new ntp_server_search();
