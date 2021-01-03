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
	// $this->dbts10();
	// $this->d20();
	$this->d30();
	$this->d40();
    }
    
    private function p10() {
	$rows = $this->tcoll->find();
	
	$sd = new stddev();
	
	foreach($rows as $a) {
	   $bt = $a['Uns'] - intval(round($a['tsc'] * self::spt10));
	   $this->bt10($bt, $a);
	}

	return;
    }
    
    private function bt10($btin, $allin) {
	
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
	$this->bts[$k]['all'][] = $allin;
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
    
    private function d20() {
	
	$co = strtotime('2021-01-02 16:30') * self::bil;
	
	foreach($this->bts as $k => $a) {
	    if ($a['boot'] < $co) continue;
	    $minns = false;
	    foreach($a['all'] as $i => $r) {
		if ($i <   4) continue;
		if ($i === 4) {
		    $minns = $r['Uns'];
		    $mints = $r['tsc'];
		}
		
		$dns = $r['Uns'] - $minns;
		$dts = $r['tsc'] - $mints;
		if ($dts === 0) continue;
		$rat = ($dns / $dts);
		$dr  = sprintf('%0.15f', $rat);
		$ds  = $dr;
		$ds .= ' ';
		$ds .= date('m/d H:i:s', $r['Uns'] / self::bil);
		$ds .= "\n";
		echo($ds);
		continue;
	    }
	}
    }
    
    private function d30() {
	$co = strtotime('2021-01-02 16:30') * self::bil;
	
	$raa = [];
	
	foreach($this->bts as $k => $a) {
	    if ($a['boot'] < $co) continue;
	    $minns = false;
	    foreach($a['all'] as $i => $r) {
		if ($i === 0) {
		    $minns = $r['Uns'];
		    $mints = $r['tsc'];
		}
		
		$dns = $r['Uns'] - $minns;
		$dts = $r['tsc'] - $mints;
		if ($dts === 0) continue;
		$rat = ($dns / $dts);
		$dr  = sprintf('%0.15f', $rat);
		// $ds  = $dr;
		// $ds .= ' ';
		// $ds .= 
		$rk = date('m/d H:i:s', $r['Uns'] / self::bil);
		if (!isset($raa[$rk])) $raa[$rk] = new stddev();
		$raa[$rk]->put($rat);
	    	// $ds .= "\n";
		// echo($ds);
		continue;
	    }
	}

	foreach($raa as $k => $r) {
	    $g = $r->get();
	    unset($g['dat']);
	    $g['hu'] = $k;
	    // var_dump($g);
	    $s = $g['a'] - self::spt10;
	    echo($s . ' ' . $g['s'] . ' ' . $k . "\n");
	}
	
    }
    
    public static function d40() {
	
    }
    
    public static function nstohu($ns) { 
	$s = intval(round($ns / self::bil));
	return date('r', $s); 
    } 
    
    // public static function 
}

if (didCLICallMe(__FILE__)) new ticks_anal();