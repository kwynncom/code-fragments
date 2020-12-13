<?php

require_once('tick.php');
require_once('stddev.php');

class tick_time_study_30 extends tick_time_study {
    public function __construct() {
	parent::__construct();
	$this->p10();
    }
    
    private function p10() {
	$res = $this->tcoll->find([], ['sort' => ['Uns' => -1]]);
	$n   = count($res);

	for ($i=0; $i + 2 < $n; $i++) {
	    
	    // big number is a nanosecond timestamp - Friday, December 4, 2020 10:58:13 PM GMT
	    // I am removing seconds per CPU tick precision out of paranoia
	    $d = $res[$i]['Uns'] - $res[$i]['tick'] * 0.3 /* ... */ - 1607122693590916268;
	    echo($d . "\n");
	    
	}
    }
    

}

new tick_time_study_30();
