<?php

require_once('storeTicks.php');

class triplet_anal extends ticks_tracker {
    
    const bil   = 1000000000;
    
    public function __construct() {
	parent::__construct(1);
	$this->p10();
    }
    
    private function p10() {
	$res = array_column($this->mcoll->find(), 'maxd');
	sort($res);
	for ($i=0; $i < count($res); $i++) {
	    echo $res[$i] . ' ' . $i . "\n";
	}
	return;
    }
    
}

if (didCLICallMe(__FILE__)) new triplet_anal();