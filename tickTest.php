<?php

require_once('tick.php');

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
	
	$tot = 0;
	$acnt = 0;
	for ($i=0; $i + 2 < $n; $i++) {
	    $dti = $res[$i]['Uns' ] - $res[$n-1]['Uns' ] ;
	    
	    if ($dti / pow(1,9) < 1200) continue;
	    
	    $dtk = $res[$i]['tick'] - $res[$n-1]['tick'] ;
	    $r = ($dtk / $dti); 
	    $d = $r - 2.6599790323801;
	    if (abs($d) > 1E-6) {
		continue;
	    }
	    echo($r . ' ' . $d . "\n");
	    $tot += $r; $acnt++;
	}
	
	echo(($tot / $acnt) . "\n");
	
	
    }
}

new tick_time_study_20();