<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/t3.php');

class IQTask5 {
	
	const osetn  = 2;
	// const ipsetn 
	const orient = [0, 90, 180, 270];
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		$ra = [];
		$issame = random_int(0, 1);
		$c1     = random_int(0, 1);
		if ($issame) $c2 = $c1;
		else		 $c2 = random_int(0, 1);
		$pa = self::orient;
		$o1 = IQTask3::retAndElim($pa);
		$o2 = IQTask3::retAndElim($pa);
		return;
		
		

	}
}

if (didCLICallMe(__FILE__)) new IQTask5();
