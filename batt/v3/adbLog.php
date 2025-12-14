<?php

declare(strict_types=1);

require_once('adbDevices.php');
require_once('usb.php');

use React\Stream\ReadableResourceStream;
use ReactLineStream\LineStream;
use React\EventLoop\Loop;

final class ADBLogReaderCl
{

    // const adbService = 'BatteryService';
    // const cmd = 'adb logcat ' . self::adbService . ':D *:S 2>&1';
    const cmd = 'adb logcat 2>&1';

    private	     object $lines;
    private readonly object $loop;
    private mixed  $inputStream;

    private readonly mixed $cb;

    private readonly bool  $termed;


    public function __construct(object $cb) {
	$this->loop = Loop::get();
	$this->cb = $cb;
	$this->reinit('init');
    }

    private function checkDat(string $line) {

	$this->cb->adbLogLine($line); 

	// if (strpos($line, self::adbService) !== false) {    $this->cb->batteryDat($line); 	}
	if (trim($line) === '- waiting for device -') {
	    belg($line);
	    $this->cb->notify('adblog', 'waiting');
	}

    }



    public function __destruct() { $this->close('destructor'); }


    private function reinit(string $ev) {
	
	belg('logcat r-einit event ' . $ev);

	if ($ev !== 'init') {
	    $this->cb->notify('adblog', 'reinit');
	} 

	if ($this->termed ?? false) return;

	if ($ev !== 'init') $this->close('reiniting');


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

	if ($this->termed ?? false) return;
	if ($ev === 'term') $this->termed = true;

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