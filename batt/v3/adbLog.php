<?php

declare(strict_types=1);

require_once('adbDevices.php');


use React\Stream\ReadableResourceStream;
use ReactLineStream\LineStream;
use React\EventLoop\Loop;

final class ADBLogReaderCl
{
    private	     string $cmd;

    private	     object $lines;
    private readonly object $loop;
    private mixed  $inputStream;

    private readonly mixed $cb;
    private readonly bool  $termed;

    private bool $isOpen = false;

    public function __construct(object $cb) {
	$this->loop = Loop::get();
	$this->cb = $cb;
	$this->reinit('init');
	
    }

    public function logRestart() {
	$this->reinit('ext');
    }

    public function __destruct() { $this->close('destructor'); }

    private static int $nlines = 0;

    private function reinit(string $ev) {

	if ($ev === 'ext' && $this->isOpen) {
	    belg('log restart sent but already open');
	    return;
	}

	$this->isOpen = false;
	self::$nlines = 0;

	belg('logcat r-einit event ' . $ev);
	if ($ev !== 'init') { $this->cb->notify('adblog', $ev);	} 
	if ($this->termed ?? false) return;
	if ($ev !== 'init') $this->close('r-einiting');

	if ($ev !== 'close') $this->init();

    }

    private function setCmd() : string {

	$c  = '';
	// $c .= 'adb wait-for-device 2>&1 && ';

	$pre  = $this->cb->shcmo->adbPrefix();
	$c  .= $pre . 'logcat -c 2>&1 && ' . $pre . 'logcat 2>&1';
	$this->cmd = $c;
	return $c;
    }

    private static int $iatts = 0;
    const sleepForRI = 5;

    private function slowRILoop() {

	self::$nlines = 0;

	if (++self::$iatts > 5) {
	    belg(self::$iatts . ' log init attempts.  Sleeping for ' . self::sleepForRI);
	    if (self::sleepForRI) sleep(self::sleepForRI);
	}
    }


    private function doLine(string $line) {
	if (++self::$nlines > 30) {
	    self::$iatts = 0;
	}

	$this->cb->adbLogLine($line);
    }

    private function init() {
	$this->slowRILoop();
	$this->setCmd();
 	belg($this->cmd);
        kwas($this->inputStream = popen($this->cmd, 'r'), 'Cannot open stream: ' . $this->cmd);
  	$this->lines = new LineStream(new ReadableResourceStream($this->inputStream, $this->loop));
        $this->lines->on('data' , function (string $line)   { $this->doLine($line); });
	$this->lines->on('close', function ()		    { $this->reinit('close');	    });
	$this->isOpen = true;
    }

    public function isOpen() : bool { return $this->isOpen; }

    public function close(string $ev): void   
    { 
	$this->isOpen = false;
	belg($this->cmd ?? '(adbLog command not set yet) ' . ' closing event ' . $ev);
	if ($this->termed ?? false) return;
	if ($ev === 'term') $this->termed = true;
	if (isset($this->lines)) {  $this->lines->close(); }
	unset(    $this->lines);
	if (isset($this->inputStream) && is_resource($this->inputStream) && 
		   ($meta = @stream_get_meta_data($this->inputStream)) &&
		   !empty($meta['stream_type'])) pclose($this->inputStream); 
	unset(    $this->inputStream);

    }
}

if (didCLICallMe(__FILE__)) {
    new ADBLogReaderCl();
}