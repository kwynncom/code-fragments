<?php

require_once('utils.php');

class IQTask4Back {
	
	public readonly object $quaps;
	
	const qfile = __DIR__ . '/../dat/t4Qs.txt';
	private readonly array $oqa;
	private readonly string $oanswer;
	const clminw = 3;
	const answern = self::clminw - 1;
	
	public function __construct() {
		$this->do10();
		$o = new stdClass();
		$o->q = $this->oqa;
		$o->correctAnswer = $this->oanswer;
		putQ($o);
		$this->quaps = $o;
		
	}
	
	private function do10() {
		$t = trim(file_get_contents(self::qfile));
		$qs = explode("\n", $t);
		$qa = [];

		$q = $qs[random_int(0, count($qs) - 1)];
		
		$ia20 = preg_split('/[^a-z]+/', $q); kwas(count($ia20) >= self::clminw, 'not enough words - 2211');
		$ia30[0] = $ia20[0];
		$ia30[1] = $ia20[1];
		$ia30[2] = $ia20[random_int(self::clminw - 1, count($ia20) - 1)];
		
		
		$ia = $ia30;
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
