<?php

require_once('utils.php');

class IQTask4Back {
	const qfile = __DIR__ . '/../t4Qs.txt';
	const wordn = 3;
	const answern = self::wordn - 1;
	public readonly array $oqa;
	public readonly string $oanswer;
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		$t = trim(file_get_contents(self::qfile));
		$qs = explode("\n", $t);
		$qa = [];
		foreach($qs as $q) 	{
			$ia = preg_split('/\s+/', $q); kwas(count($ia) === self::wordn, 'should be 3 words - 0229');
			$answer = $ia[self::answern];
			$tq = ['answer' => $answer];
			if (!isset($this->oanswer)) $this->oanswer = $answer;
				
			$oa = [];
			foreach($ia as $w) $oa[] = retAndElim($ia);
			$tq['display'] = implode(' ', $oa);
			$qa[] = $tq;
			if (iscli()) print_r($tq);
			
		}
		
		$this->oqa = $oa;
		
	}
}

if (didCLICallMe(__FILE__)) new IQTask4Back();
