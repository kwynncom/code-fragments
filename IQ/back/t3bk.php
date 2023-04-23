<?php

require_once('/opt/kwynn/kwutils.php');
require_once('utils.php');

class IQTask3Back {
	
	const clmax = 16;
	const clmin =  1;
	const clcnt =  3;
	
	public function __construct() {
		$this->do05();
	}
	
	private function do05() {
		$i = 0;
		do {
			$ret = false;
			try { $ret = $this->do10(); } catch(Exception $ex) { }
		} while(!$ret && $i++ < 500);
	}
	
	private function do10() {
		
		$pa = [];
		for($i=self::clmin; $i <= self::clmax; $i++) $pa[] = $i;
		$a[] = self::retAndElim($pa);
		$a[] = self::retAndElim($pa);
		$a[] = self::retAndElim($pa);
		sort($a);
		$d1 = $a[1] - $a[0];
		$d2 = $a[2] - $a[1]; kwas($d1 !== $d2, 'difference should not be equal');
		
		$answer = $d1 > $d2 ? $a[0] : $a[2];
		if (iscli()) echo($answer);

		
		return;
			
	}
	
	public static function retAndElim(array &$a) : int | string {
		return retAndElim($a);
	}
	
}

if (didCLICallMe(__FILE__)) new IQTask3Back();
