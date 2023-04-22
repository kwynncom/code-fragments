<?php

require_once(__DIR__ . '/../../config.php');

class IQTask1Front {
	const cliamn = 1;
	
	public function __construct() {
		$this->do10();
		$this->do20();

	}
	
	private function do10() {
		require_once(IQTestIntf::backd . 't' . self::cliamn . 'bk.php');
		$cl = 'IQTask' . self::cliamn . 'Back';
		$this->obo = new $cl;
		return;		
	}
	
	private function do20() {
		require_once('t1T.php');
	}
	
}

new IQTask1Front();