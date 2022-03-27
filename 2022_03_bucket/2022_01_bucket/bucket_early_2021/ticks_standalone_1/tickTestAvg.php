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
	for ($i=0; $i + 2 < $n; $i++) {
	    $dti = ($res[$i]['Uns' ] - $res[$n-1]['Uns' ]);
	    
	    if ($dti / pow(10,9) < 1200) continue;
	    
	    $dtk = ($res[$i]['tick'] - $res[$n-1]['tick']);
	    $r = ($dti / $dtk); 
	    echo($r . "\n");
	    $sdo->put($r);

	}
	
	$fltk = $res[0]['tick'] - $tk0;
	$flti = $res[0]['Uns' ] - $ti0;
	echo("\n" . ($flti / $fltk) . "\n");
	
	$sdr = $sdo->get();
	var_dump($sdr);
	
	$avg = $sdr['a'];	
	
	$btns = intval(round($ti0 - $tk0 * $avg));
	$bts  = intval(round($btns / pow(10,9)));
	
	echo(date('r', $bts) . "\n");
	echo(date('r', $res[$n-1]['Uboot']) . "\n");
	
	// I am removing seconds per CPU tick precision out of paranoia
	echo(($res[0]['Uns'] - intval($res[0]['tick'] * 0.3 /* ... */)) . "\n");
	

    }
    

}

new tick_time_study_20();