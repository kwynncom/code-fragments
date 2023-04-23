<?php

require_once(__DIR__ . '/../common.php');

class IQTask5Front extends IQTaskFront {
	
	const othen = 2;
	
	public function __construct() {
		$this->loadBack();
		$this->do20();
	}
	
	private function do20() {
		require_once('t5T.php');
	}
}

new IQTask5Front();
