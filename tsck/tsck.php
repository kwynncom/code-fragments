<?php

require_once('/opt/kwynn/kwutils.php');

class stsck {
	
	const domain = 'kwynn.com';
	const port   = 8123;
	const cmdprefix = 'echo -n d | nc -W 1 ';
	
	
	public function __construct() {
		$this->do10();
		$this->do20();
	}
	
	private function do10() {
		$ps = ['-u', '  ' ];
		$is = ['4', '6'];
		$dp = self::domain . ' ' . self::port;
		$rra = [];
		foreach($ps as $p) foreach($is as $i) {
			$n = $p . ' -' . $i . ' ';
			$this->sws[] = $n;
			$c = self::cmdprefix . $n . ' ' . $dp . ' ';
			// echo($n);
			$rra[] = $rr = shell_exec($c);
			echo($rr);
		} // loop
		$this->orra = $rra;
	} // func
	
	private function do20() {
		$fra = [];
		$rs = [];
		foreach($this->orra as $r) {
			$ns = intval(trim($r));
			$U  = intval(floor($ns / M_BILLION));
			$rs[] = date('D n/j H:i:s', $U);
			$fra[] = ($ns - $U * M_BILLION) / M_BILLION;
		} unset($a, $r, $U); // loop
		
		for($i=0; $i < 4; $i++) echo($rs [$i] . "\n");
		for($i=0; $i < 4; $i++) printf("%0.9f\n", $fra[$i]);
		for($i=0; $i < 4; $i++) echo($this->sws[$i] . "\n");
		echo(self::cmdprefix . '' . "\n");
		echo(self::domain . "\n");
		echo(self::port . ' port' . "\n");
		echo(date('T P') . "\n");
		echo(date('e') . "\n");
		
	} // func
} // class

new stsck();
