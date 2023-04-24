<?php

require_once('/opt/kwynn/kwutils.php');

class report extends dao_generic_3 {
	
	const dbname = 'IQ';
	const coname = 'q';
	
	public function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs('q');
		$this->do10();
	}
	
	public function do10() {
		$a = $this->qcoll->find(['isc' => ['$exists' => true]], ['up_ts' => -1]);
		$tot = count($a);
		$cor = 0;
		$ms = 0;
		foreach($a as $r) {
			if ($r['isc']) $cor++;
			$ms += $r['ms'];
		} unset($r);
		
		require_once('reportT10.php');
		
	}
	
}

new report();