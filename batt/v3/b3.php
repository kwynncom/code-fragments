<?php

require_once('utils.php');
require_once('adb.php');

class battExtCl implements battExtIntf {

    const msgSeek = 'seeking USB';
    const msgRm   = 'USB disconnect...';
    const msgAdd  = 'USB connected...';

    private array $msgs;

    private string $state = 'init';

    private function beout(string $s) {

	static $curr = '';

	if (is_numeric($s)) {
	    $show = true;
	    $this->state = 'working';
	}  else $this->state = 'not';

	$show = true;
	if ($this->since(self::msgRm) > 2 && ($curr === self::msgRm) && $s === self::msgRm) $show = false;
	if ($this->since(self::msgRm) < 2)  {
	    if ($s === self::msgSeek) $show = false;
	    if ($s !== self::msgRm && !is_numeric($s)) $show = false;
	}

	// $s !== self::msgRm &&
	if ( ($this->since(self::msgAdd) < 2) && !is_numeric($s)) $show = false;

	if (	    $s === self::msgSeek && $this->state !== 'working'
		&& ($this->since(self::msgSeek, 'low') > 3)
	   ) {
	    $s = '';
	    $show = true;
	}

	if (is_numeric($s)) {
	    $show = true;
	    $this->state = 'working';
	}


	if ($show) {
	    $this->msgs[$s] = microtime(true);
	    $curr = $s;
	    beout($s);
	    sleep(2);
	}
    }

    private function since(string $s, string $tend = 'high') : float {
	if ($this->msgs[$s] ?? false) {
	    $ret = microtime(true) - $this->msgs[$s];
	    return $ret;
	}
	if ($tend === 'high') return PHP_INT_MAX;
	else return PHP_INT_MIN;
    }

    private function monitor() {

	for($i=0; $i < self::nMaxLoop; $i++) { 
	    
	    belg('checking l-evel. ' . $i . ' of max loop: ' . self::nMaxLoop . "\n");
	    if (!adbCl::doit()) {

		$sma2 = $this->since(self::msgAdd) < 2;
		if (!$sma2) {
		    self::beout(self::msgSeek);
		    $tout = self::usbTimeoutInit;
		    self::usbMonitor($tout);
		} else usleep(500000);
		belg('exited USB mon');
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
	    self::beout(self::msgRm);
	} else if 
	   (strpos($r, 'FOUND: add ') !== false) {
	    self::beout(self::msgAdd);
	}

    }

    private function usbMonitor(int $timeout = self::usbTimeoutInit) {
	$c = 'python3 ' . __DIR__ . '/usb.py ' . $timeout . ' 2>&1';		
	belg($c);
	$res = shell_exec($c);
	belg('exited shell script: ' . $res);
	$this->processUSB($res ?? '');
    }

     public function __construct() {
	$this->initSignals();
	batt_killPrev();
	beout('init');
	$this->monitor();
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
