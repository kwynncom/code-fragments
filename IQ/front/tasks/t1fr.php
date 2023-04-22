<?php

require_once(__DIR__ . '/../../config.php');

class IQTask1Front {
	const cliamn = 1;
	
	public function __construct() {
		require_once(IQTestIntf::backd . 't' . self::cliamn . 'bk.php');
		$cl = 'IQTask' . self::cliamn . 'Back';
		$o = new $cl;
		return;
		// $this->obo = new ;
	}
	
}

new IQTask1Front();