<?php

require_once('/opt/kwynn/kwutils.php');

new usebo();

class usebo {
	public function __construct() {
		$this->init();
		$this->doit();
		$this->repcon();
	}

	private function init() {
		$this->boo = new backoff();
		$this->baset = nanotime(); // for reporting only
	}
	
	private function doit() {
		for ($i=0; $i < 60; $i++) {
			$r = $this->boo->okin();
			if ($r === true) {  $this->douse(0); continue; }
			kwas(is_integer($r) && $r > 0);
			usleep($r);
			$this->douse($r);
		}

	}
	
	private function douse($delay) {
		$this->usa[] = nanotime();	
		$this->repit($delay);
	}
	
	private function repit($delay) {
		$c = count($this->usa);
		
		if (!isset($this->usa[$c - 2])) return;
		
		$sm1 = $this->usa[$c - 1];
		$sm2 = $this->usa[$c - 2];
		
		echo($c . ' ' . $delay . ' ' /* . number_format($sm1 - $sm2) */ . "\n");
	}
	
	private function repcon() {
		$c = count($this->usa);
		if ($c < 2) return;
		$e = $this->usa[$c - 1] - $this->baset;
		
		$esf = $e / M_BILLION;
		$esi = round($esf);
		
		echo($c . ' calls in ' . $esi .  ' s' . "\n");
		
		$rf = $c / $esf;
		$ri = round($rf);

		echo($ri . ' calls per s' . "\n");
		
	}
	
}


class backoff {
	
	const mind = 4;
	const maxd  = 1200;
	const scale = 3600;

	
	public function __construct() {
		$this->cav = 0;
		$this->cari = 0;
	}
	
	public function okin() {
		if ($this->cav++ === 0) return true;
		$ac = $this->cav - $this->cari;
		if ($ac <= 1) return true;
		$x = $this->x($ac);
		$this->reset($x);
		$scx = $x * self::scale;
		return $scx;
	}
	
	private function x($n) {
		$n = intval(round($n)) - 1; 
		$n = intval(round(self::mind * pow(1.05, $n)));	
		$n = $this->limit($n);
		if ($n < self::mind) return self::mind;
		return $n;
	}
	
	private function limit($n) { return $n > self::maxd ? self::maxd : $n; }
	
	private function reset($x) {
		if ($x < self::maxd) return;
		$this->cari = $this->cav;
	}
}

