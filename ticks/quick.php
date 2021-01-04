<?php

require_once('/opt/kwynn/kwutils.php');
require_once('stddev.php');
require_once('triplets.php');

class tick_time_study {

    const initMin = 4;
    const sample = 50;
    const million = 1000000;
    
    public function __construct($exec) {
        $this->p10();
    }
    
    private function p10() {
	$base = getStableNanoPK();
	for($i=0; $i < 100000; $i++) 
	{
	    // $res = $this->doit(0.00000001 * pow(1.08,$i), $base);
	    $res = $this->doit(0.3, $base);
	    $s = '';
	    $s .= sprintf('%0.14f', $res['a']);
	    $s .= ' ';
	    $s .= $res['s'];
	    $s .= "\n";
		   
	    echo($s);
	}
    }
    
    private function doit($elapsed = 0, $base) {
	
	$sdo = new stddev();

	$res['start'] = $base['Uns'];
	usleep($elapsed * self::million);
	
	for($i=0; $i < self::sample; $i++) {
	    $dat = getStableNanoPK();
	    $r = self::rat($base, $dat);
	    $sdo->put($r);
	} 
	
	$res['end'] = $dat['Uns'];
	$res['span'] = number_format($res['end'] - $res['start']);
	$sdr = $sdo->get();
	$res = array_merge($res, $sdr);
		
	return($res);
    }
    
    public static function rat($a, $b) {
	$ds  = abs($a['Uns'] - $b['Uns' ]);
	$dtk = abs($a['tsc'] - $b['tsc']);
	if ($dtk === 0) return false;
	return $ds / $dtk;
    }
    
}

if (didCLICallMe(__FILE__)) new tick_time_study(1);