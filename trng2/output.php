<?php

class rand_output {
    
    function __construct() {
	$this->bout = 0;
	$this->n    = 0;
    }
    
    
    public function out($din, $ptr = false) {
	$l =  strlen($din);
	if ($l !== 4) {
	    kwynn();
	}
	$this->bout += $l;
	$ua = unpack('l', $din);
	$int = $ua[1];
	// echo($din[0]);
	$d = sprintf('%b', $int);
	echo($d . "\n");
	// $d = number_format($int);
	
	// echo($din[2]);
	
	// echo($this->n++ . ' ' . $d . "\n");
	
	if ($this->n === 1298) {
	    kwynn();
	}
	
	
	if ($this->bout > 2000) {
	    exit(0);
	}
    }
}
