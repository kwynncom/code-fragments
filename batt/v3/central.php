<?php

declare(strict_types=1);
use React\EventLoop\Loop;


require_once('adbLevel.php');
require_once('utils.php');
require_once('adbLevel.php');
require_once('adbLog.php');

class GrandCentralBattCl {

    private readonly object $adbReader;
    
    public function __construct() {
	beout('');
	$this->checkDevices();
	$this->adbReader = new ADBLogReaderCl($this);
	new usbMonitorCl($this);
	$this->initSignals();
	battKillCl::killPrev();
	Loop::run();
    }

    public function checkDevices() {
	adbDevicesCl::doit($this);
    }

    private function doBlank() {
	beout('');
	adbLevelCl::connTrend(false, $this);
    }

    public function notify(string $from, string $type, bool $dir = null, int $n = -1) {

	if ($from === 'level') {
	    if ($dir) beout($n);
	    else $this->doBlank();
	}


	if ($from === 'adblog' && $type === 'battdat') {
	    kwas(is_bool($dir), 'should be bool err ( #031617 ) - gcb1');
	    adbLevelCl::connTrend($dir, $this);
	}

	if ($from === 'adblog' && $type === 'waiting') {
	    $this->checkDevices();
	}

	if ($from === 'adblog' && $type === 'reinit') {
	    belg('adblog true *re*init');
	    $this->doBlank();
	}

	if ($from === 'usb') $this->checkDevices();

	if ($from === 'devices') {
	    if ($type === 'perm') beout('need permission');
	    belg('devices response');
	    if ($dir) adbLevelCl::connTrend($dir, $this);
	}
    }

    private function initSignals() {
	pcntl_async_signals(true);
	pcntl_signal(SIGINT , [$this, 'exit']);
	pcntl_signal(SIGTERM, [$this, 'exit']);
    }

    public function exit() {

	beout('');
	belg('b3 e-xit called' . "\n");
	$this->adbReader->close('term');
	$loop = Loop::get();
	$loop->stop();
	
	beout('');

	exit(0);
    }

    public function __destruct() { $this->exit();  }

}

