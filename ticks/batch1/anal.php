<?php

require_once('storeTicks.php');
require_once('stddev.php');

class ticks_anal extends ticks_tracker {
	       // 0.3759425170517
    const spt10 = 0.37594242899798;
    const bil   = 1000000000;
    
    public function __construct() {
	$this->bts = [];
	parent::__construct(1);
	// $this->p10();
	// $this->dbts10();
	// $this->d20();
	// $this->d30();
	// $this->d40();
	$this->d50();
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
		$rk = date('m/d H:i:s', $r['Uns'] / self::bil);
		if (!isset($raa[$rk])) $raa[$rk] = new stddev();
		$raa[$rk]->put($rat);
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
    
    public function d40() {

	$rmin = strtotime('2021-01-02 16:20') * self::bil;
	$rmax = strtotime('2021-01-02 16:40') * self::bil;

	$rows = $this->tcoll->find(['Uns' => ['$lte' => $rmax, '$gte' => $rmin]], ['sort' => ['Uns' => 1]]);
	
	$raa = [];
	
	$i = 0;
	
	$skip = 5;
	
	$p = false;
	foreach($rows as $r) {
	    
	    if (1) {
	    if ($p === false) { $p = $r['Uns']; continue; }
	    echo(($r['Uns'] - $p) . "\n");
	    $p = $r['Uns'];
	    continue;
	    
	    
	    echo($r['Uns'] . "\n");
	    continue;
	    }
	    
	    if ($i++ < $skip) continue;
	    
	    if ($i++ === $skip + 1) {
		$minns = $r['Uns'];
		$mints = $r['tsc'];
	    }

	    if ($i++ < $skip + 9) continue;
	    
	    $dns = $r['Uns'] - $minns;
	    $dts = $r['tsc'] - $mints;
	    if ($dts === 0) continue;
	    $rat = ($dns / $dts);
	    $dr  = sprintf('%0.15f', $rat);
	    $s = '';
	    $s .= $dns . ' ' . $dts . ' ' . $rat; //  . ' ' . self::nstohu($r['Uns']);
	    $s .= "\n";
	    echo $s;
	    continue;
	}
    }
    
    private function d50() {
	$rmin = strtotime('2020-01-02 21:10') * self::bil;
	$rmax = strtotime('2025-01-02 16:40') * self::bil;

	$rows = $this->tcoll->find(['Uns' => ['$lte' => $rmax, '$gte' => $rmin]], ['sort' => ['Uns' => 1]]);

	$p = false;
	foreach($rows as $r) {
	    if ($p !== false) {
		$d = abs($r['Uns'] - $p);
		echo($d . "\n");
	    }
	    $p = $r['Uns'];
	    continue;
	}
    }
    
    public static function nstohu($ns) { 
	$s = intval(round($ns / self::bil));
	return date('r', $s); 
    } 
    
    // public static function 
}

if (didCLICallMe(__FILE__)) new ticks_anal();