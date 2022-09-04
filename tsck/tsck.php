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
		$this->cmdso = [];
		foreach($ps as $p) foreach($is as $i) {
			$n = $p . ' -' . $i . ' ';
			$this->sws[] = $n;
			$c = self::cmdprefix . $n . '' . $dp . '';
			// echo($n);
			$rra[] = $rr = shell_exec($c);
			echo($c . ' ' . $rr . "\n");
			$this->cmdso[] = $c;
		} // loop
		$this->orra = $rra;
	} // func
	
	public function getCmdsS() { 
		$s = '';
		foreach($this->cmdso as $i => $v) {
			$s .= ($i + 0) . ' ' . $v . "\n";
		}
		
		return $s;
		
	}
	
	private function do20() {
		$fra = [];
		$rs = [];
		foreach($this->orra as $r) {
			if (!isset($r) || !$r) continue;
			$ns = intval(trim($r));
			$U  = intval(floor($ns / M_BILLION));
			$rs[] = date('D n/j H:i:s', $U);
			$fra[] = ($ns - $U * M_BILLION) / M_BILLION;
		} unset($a, $r, $U); // loop
		
		for($i=0; $i < 4; $i++) if (isset($rs [$i])) echo($i . ': ' . $rs [$i] . "\n");
		for($i=0; $i < 4; $i++) if (isset($fra[$i])) printf("%d %0.9f\n", $i, $fra[$i]);
		echo(date('T P') . "\n");
		echo(date('e') . "\n");
	} // func
} // class
