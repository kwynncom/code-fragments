<?php

require_once('/opt/kwynn/kwutils.php');
require_once('stddev.php');
require_once('triplets.php');

class tick_time_study {

    const initMin = 4;
    const sample = 50;
    const million = 1000000;
    const rat10 = 0.37594254660507;
    
    public function __construct($exec) {
	// $this->getStableRat();
	$this->setBase();

        // $this->p10();
    }
   
    private function setBase() {
	$res = getStableNanoPK();
	$this->baseuns = $res['Uns'];
	$this->basetsc = $res['tsc'];
    }
        
    private function p10() {
	
	for($i=0; $i < 200; $i++) 
	{

	    if (0) {
		$s = '';
	    $s .= number_format($res['a']);
	    $s .= ' ';
	    $s .= number_format(intval($res['s']));
	    }
	    
	    if (1) {
		$s = '';
		$s .= sprintf('%0.14f', $res['a']);
		$s .= ' ';
		$s .= $res['s'];		
	    }
	    $s .= "\n";
		   
	    echo($s);
	}
    }

    private function getStableRat($untilsd = 1E-9) {

	$base = getStableNanoPK();
	for($i=0; $i < 10; $i++) {
	    $res = $this->doit($base);
	    if ($res['s'] < $untilsd) {
		$res['iter'] = $i;
		var_dump($res);
		return $res['a'];
	    }
	}
	    
	kwas(false, 'tick ration standard deviation fail');
	
    }
    
    private function doit($base) {

	$sdo = new stddev();
	for($i=0; $i < self::sample; $i++) {
	    $dat = getStableNanoPK();
	    $rat = self::rat($base, $dat);
	    $sdo->put($rat);
	} 
	
	$sdr = $sdo->get();
		
	return($sdr);
    }
    
    public static function rat($a, $b) {
	$ds  = abs($a['Uns'] - $b['Uns' ]);
	$dtk = abs($a['tsc'] - $b['tsc']);
	if ($dtk === 0) return false;
	return $ds / $dtk;
    }
    
    public static function resetTimeFromArr($a, $b) {
	$rat = self::rat($a, $b);
	$res = intval(round($b['Uns'] - $b['tsc'] * $rat));
	return $res;
    }
    
}

if (didCLICallMe(__FILE__)) new tick_time_study(1);