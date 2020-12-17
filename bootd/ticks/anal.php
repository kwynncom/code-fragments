<?php

require_once('tick.php');
require_once('stddev.php');

class tick_study_1217_1 extends tick_time_study {
    
    const initSS = 25;
    const initMin = 4;
    const initMinPure = 15;
    const min_nptk = 0.3;
    const max_nptk = 0.49;
    
    public function __construct() {
	parent::__construct(self::dbName, __FILE__);
	$this->do10();
    }
    
    private function do10() {
	$res = $this->tcoll->find();
	$avg = $this->initavg($res);
	$this->test10($res, $avg);
	$this->test20($res, $avg);

    }

    private static function ipick($n) {
	return random_int(self::initMin, $n - 1); 
    }
    
    private static function cmp($a, $b) {
	$ds  = abs($a['Uns']  - $b['Uns' ]);
	$dtk = abs($a['tick'] - $b['tick']);
	if ($dtk === 0) return false;
	
	$r = $ds / $dtk;

	if ($r < self::min_nptk || $r > self::max_nptk) return false;
	return $r;
    }
    
    private function initavg($a) {
	
	static $ss = self::initSS;
	
	$n = count($a);
	if ($n < $ss) die('sample too small - initavg()');
	
	$sdo = new stddev();
	
	$s = [];
	for($i=0; $i < $ss - 5; $i++) {
	    $r = self::cmp($a[self::ipick($n)], $a[self::ipick($n)]);
	    if (!$r) continue;
	    if ($i === 0) $rs[] = $r;
	    if (abs($r - $rs[0]) > 0.0001) continue;
	    $rs[] = $r;
	    $sdo->put($r);
	    continue;
	}
	
	kwas(count($rs) > self::initMinPure, 'sample too small 2 - initavg()' );
	
	foreach($rs as $r) echo($r . "\n");
	$sda = $sdo->get();
	var_dump($sda);
	
	kwas($sda['s'] < pow(10, -5), 'data not good enough - stadard deviation - initavg()');
	kwas($sda['n'] > self::initMinPure, 'sample too small 3 - initavg() - just a check on the above' );
	
	return $sda['a'];	
    }
    
    private function test10($ain, $avg) {
	
	$bn = pow(10, 9);

	foreach($ain as $v) {
	    $nssinceboot = intval(round($v['tick'] * $avg));
	    $bootns = $v['Uns'] - $nssinceboot;
	    $boots  = $bootns / $bn;
	    $d1 = sprintf('%d', $bootns);
	    echo($d1 . ' ' . $boots . "\n");
	    continue;

	}
	
    }
    
    private function test20($ain, $avg) {
	
	$time0 = $ain[20]['Uns'];
	$tick0 = $ain[20]['tick'];
	
	$pv = false;
	
	foreach($ain as $v) {	
	    $uns = $v['Uns'];
	    echo($uns . ' ');
	    $eltk = $v['tick'] - $tick0;
	    $est  = $eltk * $avg + $time0;
	    $dis = sprintf('%d', $est);
	    echo($dis . ' ');
	    $d = $uns - $est;
	    echo($d . ' ');
	    
	    if ($pv) {
		$d2 = $v['Uns'] - $pv['Uns'];
		echo($d2 . ' ');
		echo($d / $d2 . ' ');
	    }
	    
	    echo("\n");
	    $pv = $v;
	    
	}
    }
    
}

if (didCLICallMe(__FILE__)) new tick_study_1217_1();