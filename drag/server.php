<?php

require_once('/opt/kwynn/kwutils.php');

class dragDemo extends dao_generic_3 {
	public function __construct() {
		$this->init();
		$this->set();
	}
	
	private function init() {
		parent::__construct('dragEx');
		$this->creTabs('order');
		
	}
	
	private function set() {
		if (isrv('action') !== 'setOrder') return;
		$id = isrv('id');
		kwas(preg_match('/^e_[A-Z]$/', $id), 'bad id');
		$or = isrv('ordx');
		kwas(is_numeric($or), 'bad orderx');
		$ox = floatval($or); unset($or);
		kwas($ox > 0, 'ordx must be positive');
		$q = ['_id' => $id];
		$dat = $q;
		$dat['ordx'] = $ox;
		$this->ocoll->upsert($q, $dat);

		
		
		
		return;
	}
	
	
}

new dragDemo();