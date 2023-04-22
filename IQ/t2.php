<?php

require_once('/opt/kwynn/kwutils.php');

class IQTask2 {
	
	const cln = 4;
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
	
		for ($i=0; $i < 26; $i++) $aa[$i] = $i;

		$ra = [];
		for ($i=0; $i < self::cln; $i++) {
			$si = random_int(0, count($aa) - 1);
			$ra[$i] = $aa[$si];
			unset(    $aa[$si]);
			$aa = array_values($aa);
			continue;
		}
		
		print_r($ra);

		
	}
}

new IQTask2();
