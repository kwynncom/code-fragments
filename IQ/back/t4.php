<?php

require_once('/opt/kwynn/kwutils.php');
require_once('t3.php');

class IQTask4 {
	const qfile = __DIR__ . '/t4Qs.txt';
	const wordn = 3;
	const answern = self::wordn - 1;
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		$t = trim(file_get_contents(self::qfile));
		$qs = explode("\n", $t);
		$qa = [];
		foreach($qs as $q) 	{
			$ia = preg_split('/\s+/', $q); kwas(count($ia) === self::wordn, 'should be 3 words - 0229');
			$tq = ['answer' => $ia[self::answern]];
			$oa = [];
			foreach($ia as $w) $oa[] = IQTask3::retAndElim($ia);
			$tq['display'] = implode(' ', $oa);
			$qa[] = $tq;
			print_r($tq);
			
		}
		$this->oqa = $qa;		
		
	}
}

new IQTask4();
