<?php

require_once('/opt/kwynn/kwutils.php');
require_once('stddev.php');
require_once('triplets.php');

class tick_time_study {

    const initMin = 4;
    const sample = 50;
    const million = 1000000;
    
    public function __construct($exec) {
        $this->doit();
    }
    
    private function doit() {
	
	$sdo = new stddev();
	
	for($i=0; $i <= self::sample; $i++) {
	    $dat = getStableNanoPK();
	    if ($i === 0) { $base = $dat; continue; }
	    $r = self::rat($base, $dat);
	    $sdo->put($r);
	} 
	
	echo(number_format($dat['Uns'] - $base['Uns']) . "\n");
	
	
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