<?php

require_once('../tick.php');
require_once('../stddev.php');

class tick_time_study_20 extends tick_time_study {
    public function __construct() {
	parent::__construct();
	$this->p10();
    }
    
    private function p10() {
	$res = $this->tcoll->find([], ['sort' => ['Uns' => -1]]);
	$n   = count($res);
	
	$tk0 = $res[$n-1]['tick'];
	$ti0 = $res[$n-1]['Uns' ];
	
	$sdo = new stddev();
	for ($i=0; $i + 2 < $n; $i++) {
	    $dti = ($res[$i]['Uns' ] - $res[$n-1]['Uns' ]);
	    
	    if ($dti / pow(10,9) < 1200) continue;
	    
	    $dtk = ($res[$i]['tick'] - $res[$n-1]['tick']);
	    $r = ($dtk / $dti); 
	    $sdo->put($r);

	}
	
	$sdr = $sdo->get();
	var_dump($sdr);
	
	$avg = $sdr['a'];	
	
	$btns = $ti0 - $tk0 / $avg;
	$bts  = intval(round($btns / pow(10,9)));
	
	echo(date('r', $bts) . "\n");
	echo(date('r', $res[$n-1]['Uboot']));	
	

    }
    

}

new tick_time_study_20();