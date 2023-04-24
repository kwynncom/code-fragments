<?php

require_once('/opt/kwynn/kwutils.php');

class IQDB extends dao_generic_3 {
	const qcnm = 'q';
	const dbname = 'IQ';
	
	public function __construct() {
		parent::__construct(self::dbname);
	}
	
}
