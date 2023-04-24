<?php

require_once(__DIR__ . '/../config.php');

class IQTestLoadTask {
	
	const oboil = __DIR__ . '/boiler.php';
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		$n = isrv('n' ); kwas($n && is_numeric($n), 'invalid incoming task n - 1801');
		$n = intval($n); kwas($n >= 1 && $n <= IQTestIntf::tasksn, 'invalid task n - 1805');
		require_once(self::oboil);
		require_once(__DIR__ . '/tasks/t' . $n . '/t' . $n . 'fr.php');
		
	}	
}

new IQTestLoadTask();
