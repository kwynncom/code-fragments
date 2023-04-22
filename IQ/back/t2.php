<?php

require_once('/opt/kwynn/kwutils.php');

class IQTask2 {
	
	const cln = 4;
	
	public function __construct() {
		$this->omacnt = 0;
		$this->do10();
		$this->do20();
	}
	
	private function do20() {
		
		static $la = [];
		if (!$la) $la = [65, 97];
		$a = $this->othea;
		$uls = random_int(0,1);
		
		for ($j=0; $j < 2; $j++) {
		for ($i=0; $i < count($a); $i++) {
				echo(chr($a[$i][$j] + $la[($j +  $uls) % 2]));
				echo(' ');
			}
			
			echo("\n");
		}
		
		echo("\n\n\n" . $this->omacnt);
		
	}
	
	private function do10() {
	
		for ($i=0; $i < 26; $i++) $aa[$i] = $i;

		$ra = [];
		for ($i=0; $i < self::cln; $i++) 
		for ($j=0; $j < 2        ; $j++)
		{

			if ($j === 0) $ism = random_int(0,1);
			else		  $ism = 0;
		
			if ($j === 0 || !$ism) {
				$si = random_int(0, count($aa) - 1);
				$ra[$i][$j] = $aa[$si];			
			}
			
			if ($ism)  { 
				$this->omacnt++;
				$ra[$i][1] = $ra[$i][0]; 
				break; 
			}
			
			unset(        $aa[$si]);
			$aa = array_values($aa);
			continue;
		}
		
		$this->othea = $ra;

	}
}

new IQTask2();
