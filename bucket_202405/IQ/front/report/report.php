<?php

require_once(__DIR__ . '/../../config.php');

class report extends IQDB {

	public function __construct() {
		parent::__construct();
		$this->creTabs(self::qcnm);
		$this->do10();
	}
	
	public function do10() {
		$a = $this->qcoll->find(['gotCorrect' => ['$exists' => true]], ['sort' => ['up_ts' => -1]]);
		$tot = count($a);
		$cor = 0;
		$ms = 0;
		foreach($a as $r) {
			if ($r['gotCorrect']) $cor++;
			$ms += $r['ms'];
		} unset($r);
		
		require_once('reportTBase.php');
		
	}
	
}

new report();