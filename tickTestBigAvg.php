<?php

require_once('tick.php');
require_once('stddev.php');

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
	
	$tti = 0;
	$ttk = 0;
	$vvs = 0;
	for ($i=0; $i + 2 < $n; $i++) {
	    $dti = $res[$i]['Uns' ] - $ti0;
	    if ($dti / pow(10,9) < 1200) continue;
	    $tti += $dti;
	    $dtk = $res[$i]['tick'] - $tk0;
	    $ttk += $dtk;
	    $vvs++;
	}
	
	$ttkm  = $ttk * pow(10,4);
	$tpsm  = $ttkm / $tti;
	$secem = $tk0 / $tpsm;
	
	$bts  = intval(($ti0 - $secem * pow(10,4)) / pow(10,9));
	
	echo(date('r', $bts) . "\n");
	echo(date('r', $res[$n-1]['Uboot']));	
	

    }
    

}

new tick_time_study_20();