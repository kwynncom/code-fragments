<?php

class ntp_output {
    public function __construct() {
	$this->sdo = new stddev();	
    }
    
    public function outstats($doit) {
	if (!$doit) return;
	var_dump($this->sdo->get());
    }
    
    public function badout() {
	if (!isset($this->badrd)) return;
	foreach($this->badrd as $s) echo($s);
    }
    
    public function out($ddin) {
	
	if (isset($ddin['off'])) {
	    $v =  $ddin['off'];
	    $v *= 1000;
	    $this->sdo->put($v);
	    $vd = sprintf('%+06.2f', $v);
	    $nms = self::outnet($ddin['all']);
	    $nmsd = sprintf('%03d', $nms);
	    $s = ($vd . ' ' . $nmsd  . ' ' . $ddin['srv'] .   "\n");
	    echo($s);
	} else {
	    $s = ($ddin['status'] . ' ' . $ddin['server'] . "\n");
	    $this->badrd[] = $s;
	}	
    }
    
    private static function outnet($d) {
	$ns = self::nanoatoi($d['local']['e']) - self::nanoatoi($d['local']['b']);
	$ms = intval(round($ns / M_MILLION));
	return $ms;
    }
    
    public static function nanoatoi($ain) {
	return $ain['s'] * M_BILLION + $ain['ns'];

    }
}