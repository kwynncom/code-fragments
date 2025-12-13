<?php

declare(strict_types=1);

require_once('adbDevices.php');
require_once('usb.php');

use React\Stream\ReadableResourceStream;
use ReactLineStream\LineStream;
use React\EventLoop\Loop;

final class ADBLogReaderCl
{

    const adbService = 'BatteryService';
    const cmd = 'adb logcat ' . self::adbService . ':D *:S 2>&1';

    private	     object $lines;
    private readonly object $loop;
    private mixed  $inputStream;

    private readonly mixed $cb;

    private readonly bool  $termed;

    private function checkDevices() {
	adbDevicesCl::doit();
    }

    private function bufferTrueSend() {
	static $lat = 0;

	$now = time();

	if ($now - $lat < 7) {
	    // maybe with a higher debug level
	    // belg('discarding multiple positives in logcat');
	    return;
	}
	($this->cb)(true);
	$lat = $now;
    }

    public function sendStatus(bool $setto) {

	static $prev;

	// belg('logcat status is now ' . ($setto ? 'true' : 'false'));
	if ((!$setto) || ($prev !== true)) { 
	    ($this->cb)($setto); 
	} else if ($setto) $this->bufferTrueSend();
	
	$prev = $setto;
	
    }

    private function checkDat(string $line) {

	if (strpos($line, self::adbService) !== false) {
	    $this->sendStatus(true);
	}
	if (trim($line) === '- waiting for device -') {
	    belg($line);
	    $this->checkDevices();
	}

    }


    public function __construct(callable $cb = null) {


	$this->loop = Loop::get();

	$this->cb = $cb;


	if (true) {
	belg('calling usb');
	new usbMonitorCl();
	belg('returning from usb');
	}
	$this->reinit('init');
	
    }

    public function __destruct() { $this->close('destructor'); }


    private function reinit(string $ev) {
	
	belg('logcat r-einit event ' . $ev);

	if ($ev !== 'init') {
	    beout('');
	    belg('blanking due to pure r-einit (>0) of :' . self::cmd);
	    $this->close('reiniting');
	} 

	if ($this->termed ?? false) return;

	$this->checkDevices();
	$this->init();

    }

    private function init(

    ) {

 	belg(self::cmd);

        $this->inputStream = popen(self::cmd, 'r');
        if (!$this->inputStream) {
            throw new \RuntimeException('Cannot open stream: ' . self::cmd);
        }

        $resourceStream = new ReadableResourceStream($this->inputStream, $this->loop);
	$this->lines = new LineStream($resourceStream);
        $this->lines->on('data' , function (string $line)   { $this->checkDat($line); });
	$this->lines->on('close', function ()		    { $this->reinit('close');	    });
    }


    public function close(string $ev): void   
    { 

	belg(self::cmd . ' closing event ' . $ev);

	if ($ev === 'term') $this->termed = true;

	$this->sendStatus(false);

	if (isset($this->lines)) {
	    $this->lines->close();
	    unset($this->lines);
	}

	if (isset($this->inputStream) && is_resource($this->inputStream) && 
		   ($meta = @stream_get_meta_data($this->inputStream)) &&
		   !empty($meta['stream_type'])) pclose($this->inputStream); 

	
	unset($this->inputStream);

    }
}

if (didCLICallMe(__FILE__)) {
    new ADBLogReaderCl();
}