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

    private int $hbat = 0;
    
    private function setHeartBeatN(int $nin) {
	$this->hbn += $nin;
	$this->hbat = time();
    }

    private function resetHeartBeat() {	$this->hbn = $this->hbat = 0;    }

    private function initHeartBeat() {

	Loop::addPeriodicTimer(0.8, function ()  {
	    if (!$this->Ubf || !$this->adbReader->isOpen()) {
		$this->resetHeartBeat();
		return;
	    }

	    if ($this->hbn < 500) return;
	    if (time() - $this->hbat > 5) { $this->resetHeartBeat();  }
	    battLogCl::noop(',');
	    
	});
    }

   
    private readonly object $lineO;
    private readonly object $adbReader;
    private readonly object $usbo;
    private readonly object $shcmo;
    private	     int    $Ubf = 0;
    private	     int    $hbn = 0;
    
    public function __construct() {
	beout('');
	$this->shcmo = new shCmdCl();
	$this->resetLog();
	$this->lineO = new adbLinesCl($this);
	$this->initHeartBeat();
	$this->adbReader = new ADBLogReaderCl($this);
	$this->usbo = new usbMonitorCl($this);
	$this->initSignals();
	battKillCl::killPrev();
	Loop::run();
    }

    public function checkDevices() {
	adbDevicesCl::doit($this);
    }

    private function resetLog() {
	beout('');
	$this->Ubf;
	$this->resetHeartBeat();
	$this->checkDevices();
 
    }

    public function levelFromADBLog(int $lev) {
	static $prev;

	if (time() - $this->Ubf < 5) return;

	if ($lev !== $prev) beout($lev);
	else belg('+');
	$prev = $lev;
    }

    private function doLevelFromFile() {
	$res = adbLevelCl::getLevelFromPhoneFileActual();
	if ($res < 0) { return $this->resetLog(); }
	$this->Ubf = time();
	beout($res);
	
    }

    const fll = '- waiting for device -';

    private function checkFirstLogLine(string $line) {

	static $l;
	static $lp;
	if (!$l) { 
	    $l = strlen(self::fll);
	    $lp = $l + 3;
	}
	
	if (isset($line[$lp])) return;

	if (trim($line) !== self::fll) return;
	belg('adb log: ' . $line);
	$this->resetLog();
    }

    public function adbLogLine(string $line) {

	$this->setHeartBeatN(strlen($line));

	$this->checkFirstLogLine($line);
	if ($this->Ubf <= 0) return;
	$this->lineO->batteryLineCheck($line);
	battLogCl::noop('.');

    }

    public function notify(string $from, string $type) {

	if ($from === 'adblog' && $type === 'reinit') {
	    belg('adblog true *re*init');
	    $this->resetLog();
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

   public function doShCmd(string $which) : mixed {
	return $this->shcmo->dosh($which);
    } 

   public function __destruct() { $this->exit();  }

}
