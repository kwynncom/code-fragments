<?php

require_once('utils.php');
require_once('adb.php');

class battExtCl implements battExtIntf {

     public function __construct() {
	$this->initSignals();
	batt_killPrev();
	beout('init');
	$this->monitor();
    }

    private function monitor() {

	for($i=0; $i < self::nMaxLoop; $i++) { 
	    
	    belg('checking l-evel. ' . $i . ' of max loop: ' . self::nMaxLoop . "\n");
	    if (!adbCl::doit()) {
		belg('running USB mon');
		beout('seeking USB');
		self::usbMonitor();
		belg('exited USB mon');
		sleep(2);
	    } else {
		belg('sleeping / monitoring, steady state: ' . self::timeoutSteadyState . 's' );
		$this->usbMonitor(self::timeoutSteadyState);
	    }

	}

	beout('b3 mon loop time/n out');

	belg('e-xit per normal (for now) max loop after n iterations === ' . $i);
    }

    private function processUSB(string $r) {
	if (strpos($r, 'FOUND: remove ') !== false) {
	    beout('USB disconnect...');
	    sleep(2);
	    beout('');
	}
    }

    private function usbMonitor(int $timeout = self::usbTimeoutInit) {
	$c = 'python3 ' . __DIR__ . '/usb.py ' . $timeout . ' 2>&1';		
	belg($c);
	$res = shell_exec($c);
	belg('exited shell script: ' . $res);
	$this->processUSB($res ?? '');
    }

    public function __destruct() { $this->exit();  }


    private function initSignals() {
	pcntl_async_signals(true);
	pcntl_signal(SIGINT , [$this, 'exit']);
	pcntl_signal(SIGTERM, [$this, 'exit']);
    }

    public function exit() {
	beout('');
	belg('b3 e-xit called' . "\n");
	exit(0);
    }


}

new battExtCl();
