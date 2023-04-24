<?php

require_once(__DIR__ . '/../../config.php');

class feedServ extends IQDB {
	
	const cnm = 'feedMode';
	const _id = 'feedModeID';
	const clq = ['_id' => self::_id];
	const vfnm = 'feedMode';
	const vvs = ['feedNone', 'feedImm'];
	
	public function __construct() {
		parent::__construct();
		$this->creTabs(self::cnm);
		$this->do10();
	}
	
	private function do10() {
		$v = isrv('setTo');
		if (!$v) return $this->get();
		kwas(in_array($v, self::vvs), 'setTo invalid 1832');
		$this->fcoll->upsert(self::clq, [self::vfnm => $v]);
	}
	
	private function get() {
		$r = $this->fcoll->findOne(self::clq);
		$v = kwifs($r, self::vfnm);
		if (!$v) return;
		kwjae(['v' => $v]);
	}
	

}

new feedServ();