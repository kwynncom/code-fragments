<?php

require_once('utils.php');

class IQTask3Back {
	
	const clmax = 16;
	const clmin =  1;
	const clcnt =  3;
	
	private readonly array $olist;
	private readonly int   $oanswer;
	public readonly object $quaps;
	
	public function __construct() {
		$this->do05();
		$o = new stdClass();
		$o->q = $this->olist;
		$o->correctAnswer = $this->oanswer;
		putQ($o);
		$this->quaps = $o;
	}
	
	private function do05() {
		
		for ($i=0; $i < 500; $i++) { // The upper limit (500 or whatever) is just a sanity check; it's astronomically unlikely to get that far.
			try { 
				$this->do10(); 
				break;
			} catch(Exception $ex) { }
		} 
	}
	
	private function do10() {
		
		$pa = [];
		for($i=self::clmin; $i <= self::clmax; $i++) $pa[] = $i;
		$a[] = self::retAndElim($pa);
		$a[] = self::retAndElim($pa);
		$a[] = self::retAndElim($pa);
		$uns = $a;
		sort($a);
		$d1 = $a[1] - $a[0];
		$d2 = $a[2] - $a[1]; kwas($d1 !== $d2, 'difference should not be equal');
		
		$this->olist = $uns;
		
		$answer = $this->oanswer = $d1 > $d2 ? $a[0] : $a[2];
		if (iscli()) echo($answer);

	}
	
	public static function retAndElim(array &$a) : int | string {
		return retAndElim($a);
	}
	
}

if (didCLICallMe(__FILE__)) new IQTask3Back();
