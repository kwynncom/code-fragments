<?php

require_once('utils.php');
require_once('/var/kwynn/batt/PRIVATEphones.php');
require_once('adbDevices.php');

use React\Stream\ReadableResourceStream;
use ReactLineStream\LineStream;
use React\EventLoop\Loop;


class usbMonitorCl {

    const cmd = 'udevadm monitor -s usb 2>&1';

    private readonly object $lines;
    private readonly object $loop;
    private readonly mixed  $inputStream;

    public function __construct() {
	if ($this->lsusb()) adbDevicesCl::doit();
	$this->init();
	if ($this->lsusb()) adbDevicesCl::doit();
    }

    private function checkDat(string $l) {

	static $lat = 0;

	$check = false;

	if (strpos($l, ' add ') !== false) $check = true;
	if (trim($l) === 'KERNEL - the kernel uevent') $check = true;
	
	$now = microtime(1);
	if ($check) {
	    if ($now - $lat < 1) return;
	    belg('calling adb devices PHP func (not shell)');
	    belg($l);
	    $lat = $now;
	    adbDevicesCl::doit();
	}

    }

    private function init() {

	adbDevicesCl::doit();

	$this->loop = Loop::get();

        $this->inputStream = popen(self::cmd, 'r');
        if (!$this->inputStream) {
            throw new \RuntimeException('Cannot open stream: ' . self::cmd);
        }

        $resourceStream = new ReadableResourceStream($this->inputStream, $this->loop);
	$this->lines = new LineStream($resourceStream);
        $this->lines->on('data' , function (string $line) { $this->checkDat($line); });
    }

    private static function lsusb() : bool {

	$b = microtime(true);
	$s = shell_exec('timeout -k 0.1 0.15 lsusb');
	$e = microtime(true);
	belg('l-susb took ' . sprintf('%0.3f', $e - $b) . 's');
	if (!$s || !is_string($s)) return false;

	foreach(KWPhonesPRIVATE::list as $r) {
	    if (strpos($s, $r['vidpid']) !== false) {
		belg('usb found specific device');
		adbDevicesCl::doit();
		return true;
	    }
	}

	return false;
    }
}