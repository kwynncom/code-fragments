<?php

require_once('adb.php');


class USBADBCl extends adbCl implements battExtIntf {

    public  bool|null $usb;
    private int   $timeout;
    private static bool $initV = false;
  
    private mixed $stdout;
    private mixed $stderr;
    private mixed $process;
    

    private function initMonitor() {

	$c  = '';
	$c .= ' nohup ';

	if ($this->timeout) $c .= 'timeout ' . $this->timeout . ' ';
	$c .= 'udevadm monitor -s usb ';
	$c .= '& ';

	$c = trim($c);
	belg($c);

	$descriptors = [  1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
	$this->process = $this->stdout = $this->stderr = false;


	$this->process = proc_open($c, $descriptors, $pipes); unset($c, $descriptors);
	$this->stdout = $pipes[1]; 
	$this->stderr = $pipes[2];
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

	if ($this->stderr ?? false) fclose($this->stderr);
	$this->stderr = false;

	if ($this->process ?? false) {
	    proc_terminate($this->process, SIGTERM); // SIGTERM too polite? 
	    proc_close($this->process);
	}
	$this->process = false;



    }

    protected function setADB() {
	beout('setADB() child start - pre-monitor USB');
	$this->monitorUSB();
	beout('setADB() child post-monitor USB; pre parent');
	parent::setADB();
	beout('setADB() post parent');
	
    }

    private function monitorUSB() {

	if ($this->obi++ === 0) {
	    belg('skipping monitor; i === ' . $this->obi . "\n");
	    return;
	}

	if ((time() - $this->Uon < 8) && !$this->valid) {
	    belg('u-sbMonSleep' . "\n");
	    sleep(1);
	    return;
	}

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
	$this->timeout = self::usbTimeoutInit;
	$this->initSignals();
    }

    private int $obi = 0;

    private function getLevelI() {


	$this->setADB();

    }

}

