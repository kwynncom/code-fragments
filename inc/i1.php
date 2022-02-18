<?php

require_once('/opt/kwynn/kwutils.php');

class test_seq extends dao_generic_3 implements fork_worker {
	
	const dbname = 'testurows';
	
	private function __construct(bool $init = false) {
		parent::__construct(self::dbname);
		$this->creTabs('nanr');
		if ($init) $this->ncoll->drop();
	}
	
	public static function shouldSplit (int $low, int $high, int $cpuCount) : bool { return true; }
	public  function workitI (int $low, int $high) {
		$t = [];
		
		for ($i = $low; $i <= $high; $i++) {
			
			$a = [];

			if (1) {
				$r = nanopk();
				if (0) {
					$s = '';
					foreach($r as $v) $s .= $v . '_';
					$a['_id'] = $s;
				}
				else {
					$o = (object) $r;
					$a = new stdClass();
					$a->_id = $o;
				}
				// $a = kwam($a, $r);
			
			}
			$t[] = $a;
		}

		$this->ncoll->insertMany($t);
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