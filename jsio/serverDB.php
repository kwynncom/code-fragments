<?php

require_once('/opt/kwynn/mongodb2.php');

class dao_jsio_example extends dao_generic_2 {
    const dbName = 'jsio_tests';

    public function __construct() {
		parent::__construct(self::dbName, __FILE__);
		$this->creTabs(['j' => 'jsdat']);
    }

	public function put($a) {
		$r = $this->jcoll->upsert(['uid' => $a['uid']], $a);
		return;
	}

}
