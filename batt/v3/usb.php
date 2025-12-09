<?php

require_once('adb.php');


class USBADBCl implements battExtIntf {

    private int   $timeout = self::usbTimeoutInit;
    private mixed $inhan = false;
    private int $obi = 0;

    private function toBackoff() : bool {

	$a = [3, 3, 3, 5, 5, 5, 7];
	$n = $a[$this->obi++] ?? self::usbTimeoutInit;
	$this->timeout = $n;

	return true;
    }

    private function initMonitor() {
	$c  = '';
	if ($this->timeout) $c .= 'timeout --foreground ' . $this->timeout . ' '; // --foreground responsds to control-C
	$c .= 'udevadm monitor -s usb ';
	$c = trim($c);
	belg($c);
	$this->inhan = popen($c, 'r');
    }

    public function __destruct() { belg('d-stuctor calling e-xit' . "\n"); $this->exit();     }

    private function close() {
	belg('closing usb p-rocess stuff');
	if ($this->inhan ?? false) pclose($this->inhan);
	$this->inhan = false;
    }

    public static function monitorUSB() {
	static $o;
	if (!isset($o)) $o = new self();
	$o->monitorI();
    }

    private function monitorI() {

	if (!$this->toBackoff()) return;

	$this->initMonitor();

 	$add = false;
	$rm  = false;

	belg('reading usb log' . "\n");
	while ($l = fgets($this->inhan)) {
	    $add = strpos($l, 'add') !== false;
	    $rm  = strpos($l, 'remove') !== false;
	    if ($add || $rm) {
		break;
	    }
	} unset($l);

	belg('c-lose USB()' . "\n");
	$this->close();

	if ($add) $this->setOn();
	if ($rm ) {
	    belg('u-sb removed' . "\n");
	    $this->reset();
	    beout('USB removed...');
	    sleep(2);
	    beout('');
	    // $this->exit();
	}


    }

    private int $Uon = 0;

    private function setOn() {
	belg('u-sb detected' . "\n");
	$this->Uon = time();
    }

    public static function getLevel() {

    }

    private readonly bool $exiting;

    public function exit() {

	if (!($this->exiting ?? false)) {
	    $this->exiting = true;
	} else {
	    belg('dup usb e-xiting call.  returning...' . "\n");
	    return;
	}


	belg('usb e xit called' . "\n");
	
	$this->close();

	if (($this->usb ?? null) === false) {
	    beout('USB disconnected.  E xiting...');
	    sleep(3);
	}
	beout('');
	belg('exiting now......');
	exit(0);
    }

    private function initSignals() {
	pcntl_async_signals(true);
	pcntl_signal(SIGINT , [$this, 'exit']);
	pcntl_signal(SIGTERM, [$this, 'exit']);
    }

    private function __construct() {
	$this->initSignals();
    }


}

