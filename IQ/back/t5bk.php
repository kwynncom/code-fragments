<?php

require_once('utils.php');

class IQTask5Back {
	
	public readonly array $oia;
	public readonly int   $omatches;
	
	const ocarn  = 2;
	const osetn  = 2;
	const omirn  = 1;
	const orient = [0, 90, 180, 270];
	
	public function __construct() {
		
		$this->obase = [];
		$this->omt = 0;
		
		$this->do05();
		$this->do10();
	}
	
	private function do05() {
		
		$ra = [];
		for ($i=0; $i < self::osetn; $i++) $ra[] = $this->do10();
		$this->omatches = $this->omt;
		$this->oia = $ra;
		return;
	}
	
	private function do10() {
		$ra = [];
		
		for($i=0; $i < self::ocarn; $i++) {
			$ra[$i]['i'] = random_int(0, self::omirn);
			$pa = self::orient;
			for ($j=0; $j < self::ocarn; $j++) $ra[$i]['o'] = retAndElim($pa);
		}

		if ($ra[0]['i'] === $ra[1]['i']) $this->omt++;
		return $ra;

	}
}

if (didCLICallMe(__FILE__)) new IQTask5Back();
