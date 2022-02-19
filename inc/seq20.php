<?php

require_once('/opt/kwynn/kwutils.php');

class test_seq extends dao_generic_3 implements fork_worker {
	
	const dbname = 'testseq';
	
	private function __construct(bool $init = false) {
		parent::__construct(self::dbname);
		$this->creTabs('seq');
		if ($init) $this->scoll->drop();
	}
	
	public static function shouldSplit (int $low, int $high, int $cpuCount) : bool { return true; }
	public  function workitI (int $low, int $high) {

		$cpun = multi_core_ranges::CPUCount();
		
		$snn = 1;
		$wi = 0;
		
		for ($i = $low; $i <= $high; $i++) {
			$sn = $snn + random_int(0, $cpun * 1);
			$snn = $this->getSeqProtected('base');
			$this->getSeqProtected($sn);		
		}
	}
	
	public function getSeqProtected($sn) {
		for ($i=0; $i < 1; $i++) {
			
			try {
				$sr = $this->scoll->findOneAndUpdate(['_id' => $sn], [ '$inc' =>  ['seq' => 1 ]], 
									['upsert' => true,  'returnDocument' => MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER]);
				return $sr['seq'];	
			}
			catch (Exception $ex) {	}
		}
		
		kwas(false, 'duplicate');

	}
	
	
	public static function workit  (int $low, int $high, int $workerN) { 
		$o = new self();
		$o->workitI($low, $high);
	}

	public static function kickoff() {
		new self(true);
		fork::dofork(true, 1, 6000, 'test_seq'); // , self::lfin, self::dbname, self::colla, $this->fts1);		
	}

	
	
}

if (didCLICallMe(__FILE__)) test_seq::kickoff();