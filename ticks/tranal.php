<?php

require_once('storeTicks.php');
require_once('triplets.php');

class triplet_anal extends ticks_tracker {
    
    const bil   = 1000000000;
    
    public function __construct() {
	if (0) {
	parent::__construct(1);
	$this->p10();
	}
	
	$this->p20();
    }
    
    private function p20() {
	$maxi = -1;
	$maxr = false;
	for($i=0; $i < 80000; $i++) {
	    $r = getStableNanoPK();
	    $ri = $r['i'];
	    if ($ri > $maxi) {
		$maxi = $ri;
		$maxr = $r;
	    }
	}
	
	echo $maxi . "\n";
	var_dump($maxr);
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