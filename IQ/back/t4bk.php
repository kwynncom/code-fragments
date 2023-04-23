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

		$q = $qs[random_int(0, count($qs) - 1)];
		
		$ia = preg_split('/\s+/', $q); kwas(count($ia) === self::wordn, 'should be n words - 0229');
		$refa = $ia;
		
		$answer = $ia[self::answern];
		$tq = ['answer' => $answer];

		$oa = [];
		foreach($ia as $w) $oa[] = retAndElim($ia);
		$tq['display'] = implode(' ', $oa);
		$qa[] = $tq;
		if (iscli()) print_r($tq);


		
		$this->oqa = $oa;
		$this->oanswer = $refa[2];
		
	}
}

if (didCLICallMe(__FILE__)) new IQTask4Back();
