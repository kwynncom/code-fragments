<?php

require_once('/opt/kwynn/kwutils.php');

interface IQTestIntf {
	const tasksn = 5;
	const t4Qf = __DIR__ . 't4Qs.txt';
	const backd = __DIR__ . '/back/';
	const qcnm = 'q';
	const dbname = 'IQ';
	
}

class IQDB extends dao_generic_3 implements IQTestIntf {
	public function __construct() {
		parent::__construct(self::dbname);
	}
}
