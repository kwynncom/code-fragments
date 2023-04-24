<?php

require_once('/opt/kwynn/kwutils.php');

class IQTask2Back {
	
	const cln = 4;
	
	public readonly int $ocorn;
	public readonly array $othea;
	
	public function __construct() {
		$this->do10();
		$this->do20();
	}
	
	private function do20() {
		
		static $la = [];
		if (!$la) $la = [64, 96];
		$a = $this->otmp;
		$uls = random_int(0,1);
		
		$t = '';
		$cs = [];
		$ra = [];
		
		for ($j=0; $j < 2; $j++) {
		for ($i=0; $i < count($a); $i++) {
				$ch = $cs[$j][] = chr($a[$i][$j] + $la[($j +  $uls) % 2]);
				$ra[$i][$j] = $ch;
				$t .= $ch;
				$t .= ' ';
			}
			
			$t .= "\n";
		}
		
		$this->othea = $ra;
		
		$t .= "\n" . $this->ocorn;
		
		if (iscli()) echo($t);
		
	}
	
	private function pop() {
		$aa = [];
		for ($i=1; $i <= 26; $i++) {
			if ($i === 9 || $i === 12) continue; // get rid of I and L and thus i and l; per letter of alphabet
			$aa[] = $i;
		}
		
		return $aa;
	}
	
	private function do10() {
	
		$aa = $this->pop();

		$corn = 0;
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
	
			unset(        $aa[$si]);
			$aa = array_values($aa);
			
			if ($ism)  { 
				$corn++;
				$ra[$i][1] = $ra[$i][0]; 
				break; 
			}
			

			continue;
		}
		
		$this->ocorn = $corn;
		$this->otmp = $ra;

	}
}

if (didCLICallMe(__FILE__)) new IQTask2Back();
