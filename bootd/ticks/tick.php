<?php

require_once('/opt/kwynn/kwutils.php');
require_once('/opt/kwynn/mongodb2.php');
require_once(__DIR__ . '/../bootd/' . 'boot.php');

class tick_time_study extends dao_generic_2 {
    const dbName = 'tick';

    public function __construct($exec) {
	parent::__construct(self::dbName, __FILE__);
	$this->creTabs(['t' => 'tick']);
	
	if ($exec === 1 || $exec === true) {
	    $this->init();
	    $this->doit(1200, 200000);
	}
    }
    
    private function init() {
	$booto = boot_tracker::get();
	return;
	
    }
    
    private static function mynano() {
	$fs = ['Uns', 'coren', 'tick'];
	$ns = nanopk();
	foreach($ns as $k => $ignore) if (!in_array($k, $fs)) unset($ns[$k]);
	return $ns;
	
    }
    
    private function doit($sleep = 30, $sleepI = 20000) {
	$startS = time();
	$i = 0;
	$sl = $sleepI;
	do {
	    $dat = self::mynano();
	    $this->tcoll->insertOne($dat);
	    usleep($sl);
	    $el = time() - $startS;
	    if ($el > 2 && $el < 30) sleep(1);
	    else if ($el >= 14) sleep($sleep);
	} while ($i++ < 10000);
    }
}

if (didCLICallMe(__FILE__)) new tick_time_study(1);