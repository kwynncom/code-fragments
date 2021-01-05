<?php

require_once('utils.php');

function cpu_tick_config() {
    
    if (getCPUModel() === 'Intel(R) Xeon(R) CPU X5650 @ 2.67GHz') 
	return [
	    ['i' => 0, 'v' => 50],
	    ['i' => 5, 'v' => 58],
	];
}
