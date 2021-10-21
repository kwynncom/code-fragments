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
		$this->boo = new backoff(4, 1200, 1.2);
		$this->totd = 0;
	}
	
	private function doit() {
		for ($i=0; $i < 50; $i++) {
			$r = $this->boo->next();
			$this->douse($i, $r);
		}
	}
	
	private function douse($i, $delay) {
		$this->rec[] = $delay;
		$this->repit($i, $delay);
	}
	
	private function repit($i, $delay) {
		echo(($i + 1) . ' ' . $delay . ' ' . "\n");
		$this->totd += $delay;
	}
	
	private function repcon() {
		$iuf = count($this->rec) / $this->totd;
		$sca = $iuf * 3600;
		$iui = intval(round($sca));
		echo($iui . ' iter per unit scaled' . "\n");
	}
}


class backoff {
	
	public function __construct($mind, $maxd, $pow) {
		$this->mind = $mind;
		$this->maxd = $maxd;
		$this->powv = $pow;
		$this->cav = 0;
		$this->cari = 0;
	}
	
	public function next() {
		$ac = $this->cav++ - $this->cari;
		$x = $this->x($ac);
		$this->reset($x);
		return $x;
	}
	
	private function x($n) {
		$n = intval(round($this->mind * pow($this->powv, $n)));	
		$n = $this->limit($n);
		return $n < $this->mind ? $this->mind : $n;
	}
	
	private function limit($n) { return $n > $this->maxd ? $this->maxd : $n; }
	
	private function reset($x) {
		if ($x < $this->maxd) return;
		$this->cari = $this->cav;
	}
}

