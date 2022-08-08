<?php

require_once('/opt/kwynn/kwutils.php');

class stsck {
	
	const domain = 'kwynn.com';
	const port   = 8123;
	
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		/*  echo -n d | nc -W 1 -u -6 kwynn.com 8123
			echo -n d | nc -W 1    -6 kwynn.com 8123
			echo -n d | nc -W 1 -u -4 kwynn.com 8123
			echo -n d | nc -W 1	   -4 kwynn.com 8123  *  */
		
		$ps = ['-u', '' ];
		$is = ['4', '6'];
		
		$pre = 'echo -n d | nc -W 1 ';
		
		
		foreach($ps as $p) foreach($is as $i) {
			$cmd = $pre . $p . ' -' . $i . ' ' . self::domain . ' ' . self::port;
			echo(shell_exec($cmd));
		}
		
		
		
		
		
		
	}
}

new stsck();