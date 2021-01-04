<?php

class stddev {
    public function __construct() {
	$this->sum = 0;
	$this->dat = [];
    }
    
    public function put($din) {
	if (!is_numeric($din)) return;
	$this->sum  += $din;
	$this->dat[] = $din;
    }
    
    public function get() {
	$n = count($this->dat);
	if ($n === 0) return null;
	$avg = $this->sum / $n;
	
	$min = PHP_INT_MAX;
	$max = PHP_INT_MIN;
	
	$acc = 0;
	foreach($this->dat as $v) { 
	    $acc += pow($v - $avg, 2);
	    // if ($v < $min) $min = $v;
	    // if ($v > $max) $max = $v;
	}
	$stdd = sqrt($acc / $n);
	return ['a' => $avg, 's' => $stdd, 'n' => $n /*, 'min' => $min, 'max' => $max*/];
    }
}
