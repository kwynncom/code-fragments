<?php

require_once('tick.php');
require_once('stddev.php');

class tick_study_1217_1 extends tick_time_study {
    
    const initSS = 35;
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
    }

    public static function cmp($a, $b) {
	$ds  = abs($a['Uns']  - $b['Uns' ]);
	$dtk = abs($a['tsc'] - $b['tsc']);
	if ($dtk === 0) return false;
	
	$r = $ds / $dtk;

	if ($r < self::min_nptk || $r > self::max_nptk) return false;
	return $r;
    }
    
    private function initavg($a) {
	
	$n = count($a);
	if ($n < self::initSS) die('sample too small - initavg()');
	
	$sdo = new stddev();
	
	$s = [];
	for($i=self::initMin; $i < $n - 2; $i++) {
	    $r = self::cmp($a[$i], $a[$i+1]);
	    if (!$r) continue;
	    if ($i === self::initMin) $rs[] = $r;
	    if (abs($r - $rs[0]) > 0.0001) continue;
	    $rs[] = $r;
	    $sdo->put($r);
	    continue;
	}
	
	kwas(count($rs) > self::initMinPure, 'sample too small 2 - initavg()' );
	
	foreach($rs as $r) echo($r . "\n");
	$sda = $sdo->get();
	var_dump($sda);
	
	kwas($sda['s'] < pow(10, -4), 'data not good enough - stadard deviation - initavg()');
	kwas($sda['n'] > self::initMinPure, 'sample too small 3 - initavg() - just a check on the above' );
	
	return $sda['a'];	
    }
  
}

if (didCLICallMe(__FILE__)) new tick_study_1217_1();