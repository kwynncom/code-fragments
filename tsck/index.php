<?php

require_once('/opt/kwynn/kwutils.php');

class stsck {
	
	const domain = 'kwynn.com';
	const port   = 8123;
	
	
	public function __construct() {
		$this->do10();
		$this->do20();
	}
	
	private function do10() {
		$ps = ['-u', '  ' ];
		$is = ['4', '6'];
		$pre = 'echo -n d | nc -W 1 ';
		$dp = self::domain . ' ' . self::port;
		echo($pre . '' . $dp . "\n");
		$rra = [];
		foreach($ps as $p) foreach($is as $i) {
			$n = $p . ' -' . $i . ' ';
			$c = $pre . $n . ' ' . $dp . ' ';
			echo($n);
			$rra[] = $rr = shell_exec($c);
			echo($rr);
		} // loop
		$this->orra = $rra;
	} // func
	
	private function do20() {
		$a = $this->orra;
		foreach($a as $r) {
			$ns = intval(trim($r));
			$U  = roint($ns / M_BILLION);
			echo(date('r', $U) . "\n");
		}
	}
	
} // class

new stsck();