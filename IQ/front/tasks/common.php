<?php

require_once(__DIR__ . '/../../config.php');

abstract class IQTaskFront {
	protected function loadBack() {
		$n = isrv('n');
		require_once(IQTestIntf::backd . 't' . $n . 'bk.php');
		$cl = 'IQTask' . $n . 'Back';
		$this->obo = new $cl;
		return;		
	}
}
