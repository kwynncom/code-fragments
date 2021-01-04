<?php

require_once('storeTicks.php');
require_once('triplets.php');
require_once('quick.php');

class triplet_anal extends ticks_tracker {
    
    const bil   = 1000000000;
    
    public function __construct() {
	if (0) {
	parent::__construct(1);
	$this->p10();
	}
	
	// $this->p20();
	$this->p30();
    }
    
    private function p30() {
	for($i=0; $i < 100000; $i++) {
	    $r = getStableNanoPK();
	    if ($i === 0) { $p = $r;  continue;    }
	    $rat = tick_time_study::rat($r, $p);
	    echo(sprintf('%0.14f', $rat) . "\n");
	    $p = $r;
	    usleep(100000);
	    
	}
    }
    
    private function p20() {
	$maxi = -1;
	$maxr = false;
	for($i=0; $i < 1000; $i++) {
	    $r = getStableNanoPK();
//	    echo($r['maxd']) . "\n";
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