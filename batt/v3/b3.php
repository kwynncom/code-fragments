<?php

declare(strict_types=1);

require_once('utils.php');
require_once('adb.php');
require_once('/var/kwynn/batt/PRIVATEphones.php');

class battExtCl implements battExtIntf, KWPhonesPRIVATE {

    const msgSeek = 'seeking USB';
    const msgRm   = 'USB disconnect...';
    const msgAdd  = 'USB connected...';

    private array $msgs;

    private string $state = 'init';

    private function beout(string $s) {

	static $curr = '';

	if ($s === self::msgAdd && is_numeric($curr)) {
	    return;
	}

	if (is_numeric($s)) {
	    $show = true;
	    $this->state = 'working';
	}  else $this->state = 'not';

	$show = true;
	if ($this->since(self::msgRm) > 2.5 && ($curr === self::msgRm) && $s === self::msgRm) $show = false;
	if ($this->since(self::msgRm) < 2)  {
	    if ($s === self::msgSeek) $show = false;
	    if ($s !== self::msgRm && !is_numeric($s)) $show = false;
	}

	// $s !== self::msgRm &&
	if ( ($this->since(self::msgAdd) < 2) && !is_numeric($s)) $show = false;

	if (	    $s === self::msgSeek && $this->state !== 'working'
		&& ($this->since(self::msgSeek, 'low') > 1.8)
	   ) {
	    $s = '';
	    $show = true;
	}

	if (is_numeric($s)) {
	    $show = true;
	    $this->state = 'working';
	}


	if ($show) {
	    $this->msgs[$this->msgkconv($s)] = microtime(true);
	    $curr = $s;
	    beout($s);
	    sleep(2);
	}
    }

    private function msgkconv(string $sin) : string {
	if (is_numeric($sin)) return 'numeric';
	if (!trim($sin)) return '(blank)';
	return $sin;
    }

    private function since(string $s, string $tend = 'high') : float {
	$k = $this->msgkconv($s);
	if ($this->msgs[$k] ?? false) {
	    $ret = microtime(true) - $this->msgs[$k];
	    return $ret;
	}
	if ($tend === 'high') return PHP_INT_MAX;
	else return PHP_INT_MIN;
    }

    private function to10($default) {
	static $i = 0;
	static $a = [2, 2, 3, 3];

	if ($this->since(self::msgAdd) < 5) $i = 0;

	return $a[$i++] ?? $default;
    }

    private function monitor() {

	for($i=0; $i < self::nMaxLoop; $i++) { 

	    if ($this->adbo->doit());

	    belg('checking l-evel. ' . $i . ' of max loop: ' . self::nMaxLoop . "\n");

	    sleep(2);
	}

	// beout('b3 mon loop time/n out');
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

    private function lsusb() : bool {

	$b = microtime(true);

	// in background / non-foreground mode, this still hangs; probably should put this on PHP React, of course
    	$s = shell_exec('timeout --foreground -k 0.1 0.15 lsusb');
	$e = microtime(true);
	belg('lsusb took ' . sprintf('%0.3f', $e - $b) . 's');
	if (!$s || !is_string($s)) return false;

	foreach(KWPhonesPRIVATE::list as $r) {
	    if (strpos($s, $r['vidpid']) !== false) return true;
	}

	return false;
    }

    private function usbMonitor(int $timeout = self::usbTimeoutInit, bool $isconn = false) {

	if ($this->lsusb()) {
	    
	    if (!$isconn) { 
		self::beout(self::msgAdd);
		return;
	    }
	}

	$c  = '';
	// $c .= 'nohup ';
	$c .= 'python3 ' . __DIR__ . '/usb.py ' . $timeout . ' 2>&1 ';
	// $c .= '& ';		
	$c = trim($c);
	belg($c);
	$res = shell_exec($c);
	belg('exited shell script: ' . $res);
	$this->processUSB($res ?? '');
    }

    private readonly object $adbo;

     public function __construct() {
	beout('');
	$this->adbo = new adbCl();
	$this->initSignals();
	battKillCl::killPrev();
	$this->monitor();
    }

    public function __destruct() { $this->exit();  }

    private function initSignals() {
	pcntl_async_signals(true);
	pcntl_signal(SIGINT , [$this, 'exit']);
	pcntl_signal(SIGTERM, [$this, 'exit']);
    }

    public function exit() {

	if (!battKillCl::isPrev()) beout('');
	belg('b3 e-xit called' . "\n");
	exit(0);
    }
}

new battExtCl();
