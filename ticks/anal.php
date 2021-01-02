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
	$this->dbts10();
	// $this->d20();
    }
    
    private function p10() {
	$rows = $this->tcoll->find();
	
	$sd = new stddev();
	
	foreach($rows as $a) {
	   $bt = $a['Uns'] - intval(round($a['tsc'] * self::spt10));
	   $this->bt10($bt);
	}

	return;
    }
    
    private function bt10($btin) {
	
	$k = false;
	
	foreach($this->bts as $ka => $a) {
	    if (abs($btin - $a['boot']) < self::bil * 4) {
		$k = $ka;
		break;
	    }
	}
	
	if ($k === false) {
	    $k = self::nstohu($btin);
	    $this->bts[$k]['sdo'] = new stddev();
	    $this->bts[$k]['boot'] = $btin;
	}
	
	$this->bts[$k]['sdo']->put($btin);


	
	
    }
    
    private function dbts10() {
	foreach($this->bts as $k => $bt) {
	    echo($k . ': ');
	    $sdr = $bt['sdo']->get();
	    echo(number_format(intval($sdr['s'])));
	    echo(' ' . $sdr['n']);
	    echo(' ' . number_format($sdr['max'] - $sdr['min']));
	    echo("\n");
	}
    }
    
    public static function nstohu($ns) { 
	$s = intval(round($ns / self::bil));
	return date('r', $s); 
	
    } 
    
    // public static function 
}

if (didCLICallMe(__FILE__)) new ticks_anal();