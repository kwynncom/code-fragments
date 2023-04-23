<?php

require_once(__DIR__ . '/../common.php');

class IQTask1Front extends IQTaskFront {
	
	const ann = 2;
	const cocols = 4;
	
	public function __construct() {
		$this->loadBack();
		$this->do20();

	}
	

	
	private function do20() {
		require_once('t1T.php');
	}
	
}

new IQTask1Front();