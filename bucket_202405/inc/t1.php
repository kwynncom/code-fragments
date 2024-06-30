<?php

require_once('/opt/kwynn/kwutils.php');

class test_seq extends dao_generic_3 implements fork_worker {
	
	const dbname = 'testseq';
	
	private function __construct(bool $init = false) {
		parent::__construct(self::dbname);
		$this->creTabs('seq');
		if ($init) {
			$this->scoll->updateOne(['_id' => 'boo'], ['$set' => ['seq' => 0]], ['upsert' => true]);
		}
		
	}
	
	public static function shouldSplit (int $low, int $high, int $cpuCount) : bool { return true; }
	public  function workitI (int $low, int $high) {
		if (!is_numeric($low) || !is_numeric($high)) return;
		if ($low < 1 || $high < 1) return;
		for ($i = $low; $i <= $high; $i++) 
			$this->scoll->findOneAndUpdate(['_id' => 'boo'], [ '$inc' =>  ['seq' => 1 ]], ['returnNewDocument' => true]);
	}
	
	public static function workit  (int $low, int $high, int $workerN) { 
		$o = new self();
		$o->workitI($low, $high);
	}

	public static function kickoff() {
		new self(true);
		$n = random_int(1, M_MILLION);
		echo("$n\n");
		fork::dofork(true, 1, $n, 'test_seq'); // , self::lfin, self::dbname, self::colla, $this->fts1);		
	}

	
	
}

if (didCLICallMe(__FILE__)) test_seq::kickoff();