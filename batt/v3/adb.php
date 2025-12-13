<?php
declare(strict_types=1);

require_once('adbLog.php');
require_once('wait.php');
require_once('waitUSB.php');
require_Once('adbLevel.php');

class adbCl {

    public function __construct() {
	
    }

    public function doit() {

	for($i=0; $i < 30; $i++) {

	    $this->doit20();

	    $sleep = 5;
	    belg('adb doit sleep ' . $sleep);
	    if ($sleep) { 
		sleep($sleep);
	    }
	}

    }




} // class