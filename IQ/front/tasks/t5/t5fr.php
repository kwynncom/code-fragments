<?php

require_once(__DIR__ . '/../common.php');

class IQTask5Front extends IQTaskFront {
	
	public function __construct() {
		$this->loadBack();
		$this->do20();
	}
	
	private function do20() {
		// require_once('t4T.php');
	}
}

new IQTask5Front();
