<?php

require_once('/opt/kwynn/kwutils.php');

new backoff();

class backoff {
	
	const mind = 4;
	const maxd  = 1200;
	
	private $usa;

	
	public function __construct() {
		$this->init10();
		$this->doit();
		$this->rep50();
	}
	
	private function rep50() {
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
	
	private function init10() {
		$this->usa = [];
		$this->basei = 0;
		$this->baset = nanotime();
	}
	
	private function doit() {
		for ($i=0; $i < 30; $i++) {
			$this->doit10();
			$this->rep10();
			$this->reset();
		}
		
	}
	
	private function rep10() {
		$c = count($this->usa);
		
		if (!isset($this->usa[$c - 2])) return;
		
		$sm1 = $this->usa[$c - 1];
		$sm2 = $this->usa[$c - 2];
 
		$xi = round($this->x($c - 1 - $this->basei));
		
		echo($c . ' ' . $xi . ' ' . number_format($sm1 - $sm2) . "\n");
	}
	
	private function doit10() {
		$d = nanotime();
		$this->usa[] = $d;
		$this->sleep();
	}
	
	private function x($nin) {
		$n = $nin; unset($nin);
		$n = intval(round($n)) - 1; 
		$n = intval(round(self::mind * pow(1.297, $n)));	
		$n = $this->limit($n);
		if ($n < self::mind) return self::mind;
		return $n;
	}
	
	private function limit($n) {
		$lim = self::maxd;
		if ($n > $lim) return $lim;
		else return $n;
	}
	
	private function reset() {
		$c = count($this->usa);
		$x = $this->x($c - $this->basei); 
		if ($x < self::maxd) return;
		$this->basei = count($this->usa);
	}
	
	private function sleep() {
		$v10 = $this->x(count($this->usa) - $this->basei);
		$v20 = $v10 * 360;
		usleep($v20);
	}
	
	
}

