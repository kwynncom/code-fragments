<?php

require_once('storeTicks.php');
require_once('stddev.php');

class ticks_anal extends ticks_tracker {
    
    const spt10 = 0.37594242899798;
    const bil   = 1000000000;
    
    public function __construct() {
	$this->bts = [];
	parent::__construct(self::dbName, __FILE__);
	$this->p10();
    }
    
    private function p10() {
	$rows = $this->tcoll->find();
	
	$sd = new stddev();
	
	foreach($rows as $a) {
	   $bt = $a['Uns'] - intval(round($a['tsc'] * self::spt10));
	   $this->bt10($bt);
	    
	}
	
	// var_dump($sd->get());
	
	$this->dbts10();
	
	return;
    }
    
    private function bt10($btin) {
	foreach($this->bts as $i => $bt) {
	    if (abs($btin - $bt) < self::bil * 4) return;
	    else {
		
	    }
	}

	$this->bts[self::nstohu($btin)] = $btin;
	
	
	
    }
    
    private function dbts10() {
	foreach($this->bts as $k => $bt) {
	    echo($k . "\n");
	}
    }
    
    public static function nstohu($ns) { 
	$s = intval(round($ns / self::bil));
	return date('r', $s); 
	
    }    
}

if (didCLICallMe(__FILE__)) new ticks_anal();