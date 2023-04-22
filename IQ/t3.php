<?php

require_once('/opt/kwynn/kwutils.php');

class IQTask3 {
	
	const clmax = 16;
	const clmin =  1;
	const clcnt =  3;
	
	public function __construct() {
		$this->do10();
	}
	
	public function do10() {
		
		$pa = [];
		for($i=self::clmin; $i <= self::clmax; $i++) $pa[] = $i;
		$a[] = self::retAndElim($pa);
		$a[] = self::retAndElim($pa);
		$min = min($a[0], $a[1]);
		$max = max($a[0], $a[1]);
		$elim = $max + ($max - $min);
		if ($rmi = (array_search($elim, $pa) !== false)) {
			unset($pa[$rmi]);
			$pa = array_values($pa);
		}
		$a[] = self::retAndElim($pa);
		print_r($a);
		
		sort($a);
		unset($d1);
		$d1 = $a[1] - $a[0];
		$d2 = $a[2] - $a[1]; kwas($d1 !== $d2, 'difference should not be equal');
		
		$answer = $d1 > $d2 ? $a[0] : $a[2];
		echo($answer);

		
		return;
			
	}
	
	public static function retAndElim(array &$a) : int {
		$si = random_int(0, count($a) - 1);
		$sel = $a[$si];
		unset($a[$si]);
		$a = array_values($a);
		return $sel;
	}
	
}

new IQTask3();
