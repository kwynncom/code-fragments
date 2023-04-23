<?php

require_once(__DIR__ . '/../common.php');

class IQTask3Front extends IQTaskFront {
	
	const clcols = 3;
	
	public function __construct() {
		$this->loadBack();
		$this->do20();
	}
	
	private function do20() {
		require_once('t3T.php');
	}
}

new IQTask3Front();