<?php

declare(strict_types=1);
use React\EventLoop\Loop;

require_once('utils.php');
require_once('shellCommands.php');
require_once('adbLevel.php');
require_once('adbLog.php');
require_once('adbLines.php');
require_once('adbDevices.php');


class GrandCentralBattCl {

    public function doShCmd(string $which) : mixed {
	return $this->shcmo->dosh($which);
    }

   
    private readonly object $lineO;
    private readonly object $adbReader;
    private readonly object $usbo;
    private readonly object $shcmo;
    
    
    public function __construct() {
	beout('');
	$this->shcmo = new shCmdCl();
	$this->lineO = new adbLinesCl($this);
	$this->checkDevices();
	$this->adbReader = new ADBLogReaderCl($this);
	$this->usbo = new usbMonitorCl($this);
	$this->initSignals();
	battKillCl::killPrev();
	Loop::run();
    }

    public function checkDevices() {
	adbDevicesCl::doit($this);
    }

    private function doBlank() { 	beout('');     }

    public function levelFromADBLog(int $lev) {
	static $prev;
	if ($lev !== $prev) beout($lev);
	else belg('same level');
	$prev = $lev;
    }

    private function doLevelFromFile() {
	$res = adbLevelCl::getLevelFromPhoneFileActual();
	if ($res >= 0) beout($res);
	else $this->doBlank();

	
    }

    public function adbLogLine(string $line) {
	$this->lineO->batteryLineCheck($line);
    }

    public function notify(string $from, string $type) {

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
	    if ($type === 'found') $this->doLevelFromFile();
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
	$this->usbo->close();
	PidFileGuard::release();
	beout('');

	exit(0);
    }

    public function __destruct() { $this->exit();  }

}
