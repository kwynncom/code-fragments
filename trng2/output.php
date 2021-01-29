<?php

class rand_output {
    
    function __construct() {
	$this->bout = 0;
	$this->n    = 0;
    }
    
    private function p20($din) {
	static $i = 0;
	static $c0 = false;
	static $c1 = false;
	
	if ($i++ % 2 === 0) {
	    $c0 = $din[1];
	    $c1 = $din[2];
	    return;
	}
	
	
	
	
	
    }
    
    
    public function out($din) {
	
	//  $this->p20($din);
	
	// return; // *****
	
	$l =  strlen($din);
	if ($l !== 4) {
	    kwynn();
	}
	$this->bout += $l;
	$ua = unpack('l', $din);
	$int = $ua[1]; unset($ua);
	// echo($din[0]);
	$d = sprintf('%032b', $int & 0xffff);
	// $d = sprintf('%08b', $din[2]);
	// $d = sprintf('%04b', ord($din[2]) & 15);
	// $d = number_format($int);
	// $d = log($int, 2);
	echo($d . "\n");
	
	 // echo($din[2]);
	
	// echo($this->n++ . ' ' . $d . "\n");
	
	if ($this->bout > 1000) {
	    exit(0);
	}
    }
}
