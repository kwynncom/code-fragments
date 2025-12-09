<?php


require_once('utils.php');
require_once('usb.php');
require_once('adb.php');

class battExtCl implements battExtIntf {

     public function __construct() {
	batt_killPrev();
	beout('init');
	$this->monitor();
    }

    private function monitor() {

	for($i=0; $i < self::nMaxLoop; $i++) { 
	    
	    belg('checking l-evel. ' . $i . ' of max loop: ' . self::nMaxLoop . "\n");
	    if (!adbCl::doit()) {
		belg('running USB mon');
		USBADBCl::monitor();
	    }

	    sleep(self::usbTimeoutInit);
	}

	beout('b3 mon loop time/n out');

	belg('e-xit per normal (for now) max loop after n iterations === ' . $i);
    }

    public function __destruct() { $this->exit();  }

    public function exit() {
	beout('');
	belg('b3 e-xit called' . "\n");
	exit(0);
    }


}

new battExtCl();
