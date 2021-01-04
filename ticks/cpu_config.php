<?php

require_once('utils.php');

function cpu_tick_config() {
    
    if (getCPUModel() === 'Intel(R) Xeon(R) CPU X5650 @ 2.67GHz') 
	return [
		['i' =>  5, 'm' => 470],
	        ['i' => 10, 'm' => 478],
	        ['i' => 20, 'm' => 526],
	    ];
}
