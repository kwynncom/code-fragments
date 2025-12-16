<?php

declare(strict_types=1);

require_once('adbDevices.php');
require_once('usb.php');

use React\Stream\ReadableResourceStream;
use ReactLineStream\LineStream;
use React\EventLoop\Loop;

final class ADBLogReaderCl
{
    const cmdSfx = 'logcat 2>&1';

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
	
    }

    public function start() {
	$this->reinit('init');
    }

    public function __destruct() { $this->close('destructor'); }

    private static int $nlines = 0;

    private function reinit(string $ev) {

	$this->isOpen = false;
	self::$nlines = 0;

	belg('logcat r-einit event ' . $ev);
	if ($ev !== 'init') { $this->cb->notify('adblog', 'reinit');	} 
	if ($this->termed ?? false) return;
	if ($ev !== 'init') $this->close('reiniting');

	if ($ev !== 'close') $this->init();

    }

    private function setCmd() : string {
	$c  = $this->cb->shcmo->adbPrefix();
	$c .= self::cmdSfx;
	$this->cmd = $c;
	return $c;
    }

    private static int $iatts = 0;

    private function slowReinitLoop() {

	static $sleep = 5;

	if (++self::$iatts > 3) {
	    belg(self::$iatts . ' log init attempts.  Sleeping for ' . $sleep);
	    if ($sleep) sleep($sleep);
	}
    }


    private function doLine(string $line) {
	if (++self::$lines > 30) {
	    self::$iatts = 0;
	}
    }

    private function init() {
	$this->slowReinitLoop();
	$this->setCmd();
 	belg($this->cmd);
        kwas($this->inputStream = popen($this->cmd, 'r'), 'Cannot open stream: ' . $this->cmd);
  	$this->lines = new LineStream(new ReadableResourceStream($this->inputStream, $this->loop));
        $this->lines->on('data' , function (string $line)   { $this->cb->adbLogLine($line);  });
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