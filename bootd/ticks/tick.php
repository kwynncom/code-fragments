<?php

require_once('/opt/kwynn/kwutils.php');
require_once('stddev.php');

class tick_time_study {

    const initMin = 4;
    const sample = 50;
    const million = 1000000;
    
    public function __construct($exec) {
        $this->doit(3);
    }
    
    private function doit($elapsed) {
	
	$sdo = new stddev();
	
	for($i=0; $i < self::initMin; $i++) nanopk();
	
	$base = nanopk();	
	usleep($elapsed * self::million);
	
	$startS = microtime(1);
	for($i=0; $i < self::sample; $i++) {
	    $dat = nanopk();
	    $r = self::rat($base, $dat);
	    $sdo->put($r);
	} 
		
	var_dump($sdo->get());
	return;
    }
    
    public static function rat($a, $b) {
	$ds  = abs($a['Uns'] - $b['Uns' ]);
	$dtk = abs($a['tsc'] - $b['tsc']);
	if ($dtk === 0) return false;
	return $ds / $dtk;
    }
    
}

if (didCLICallMe(__FILE__)) new tick_time_study(1);