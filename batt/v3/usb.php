<?php

require_once('adb.php');


class USBADBCl extends adbCl implements battExtIntf {

    public  bool|null $usb;
    private int   $timeout = self::usbTimeoutInit;
    private static bool $initV = false;
  
    private mixed $stdout;
    private mixed $process;

    private int $obi = 0;
    

    private function toBackoff() : bool {

	if ((time() - $this->Uon < 8) && !$this->valid) {
	    belg('u-sbMonSleep; skipping usb mon' . "\n");
	    sleep(1);
	    return false;
	}


	$a = [3, 3, 3, 5, 5, 5, 7];
	$n = $a[$this->obi++] ?? self::usbTimeoutInit;
	$this->timeout = $n;
	if ($this->obi === 1) {
	    belg('skipping usb mon due to obi');
	    return false;
	}

	return true;
    }

    private function initMonitor() {

	$c  = '';
	if ($this->timeout) $c .= 'timeout ' . $this->timeout . ' ';
	$c .= 'udevadm monitor -s usb ';

	$c = trim($c);
	belg($c);

	$descriptors = [  1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
	$this->process = $this->stdout = false;


	$this->process = proc_open($c, $descriptors, $pipes); unset($c, $descriptors);
	$this->stdout = $pipes[1]; 
	unset($pipes);

    }

    public function __destruct() {
	belg('d-stuctor calling e-xit' . "\n");
	$this->exit();
	
    }

    private function close() {

	belg('closing usb process stuff');

	if ($this->stdout ?? false) fclose($this->stdout);
	$this->stdout = false;

	if ($this->process ?? false) {
	    proc_terminate($this->process, SIGTERM); // SIGTERM too polite? 
	    proc_close($this->process);
	}
	$this->process = false;



    }

    protected function setADB() {
	belg('setADB() child start - pre-monitor USB');
	$this->monitorUSB();
	belg('setADB() child post-monitor USB; pre parent');
	parent::setADB();
	belg('setADB() post parent');
	
    }

    private function monitorUSB() {

	if (!$this->toBackoff()) return;

	$this->initMonitor();

    	unset($this->usb);
	$add = false;
	$rm  = false;

	belg('reading usb log' . "\n");
	while ($l = fgets($this->stdout)) {
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
	    $this->usb = $rm;
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
	$this->usb = true;
	$this->Uon = time();
    }

    public static function getLevel() {
	static $o;

	if (!isset($o)) $o = new self();
	$o->getLevelI();
	return $o;
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

    private function getLevelI() {


	$this->setADB();

    }

}

