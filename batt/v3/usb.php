<?php

require_once('utils.php');

class USBADBCl implements battExtIntf {

    private int   $timeout = self::usbTimeoutInit;

    private mixed $inhan = false;
    private mixed $process = false;

    private readonly bool $standalone;
    private readonly bool $exiting;


    private function __construct(bool $standalone) {
	$this->standalone = $standalone;
	$this->initSignals();
    }

    public static function runShellScript() {
	$c = 'python3 ' . __DIR__ . '/usb.py 2>&1';		
	// $c = 'bash ' . __DIR__ . '/usb.sh 2>&1';
	belg($c);
	$res = shell_exec($c);
	belg('exited shell script: ' . $res);
    }

    private function initMonitor() {
	$c  = '';
	if (false) {
	if ($this->standalone) 
	{
	    if ($this->timeout) {
		$c .= 'timeout ';
		$c .= '--foreground ';
		$c .= $this->timeout . ' ';
	    }
	    $c .= 'udevadm monitor -s usb ';
	} else { 
	    $c .= 'php ' . __FILE__;
	}
	}

	$c .= 'bash ' . __DIR__ . '/usb.sh';

	$c = trim($c);
	belg($c);

	$descriptors = [
	    1 => ['pipe', 'w'],              // stdout - we want this
	];

	$this->process = proc_open($c, $descriptors, $pipes);
	$this->inhan   = $pipes[1];
    }

    public function __destruct() { belg('d-stuctor calling e-xit' . "\n"); $this->exit();     }

    private function close() {
	belg('closing usb p-rocess stuff');


	if ($this->process ?? false) {
	    proc_terminate($this->process);

	    if ($this->inhan ?? false) fclose($this->inhan);
	    $this->inhan = false;

	    proc_close($this->process);
	
	}
	$this->process = false;
    }

    public static function monitor(bool $standalone = false) {
	static $o;
        if (!isset($o)) $o = new self($standalone);
        $o->monitorI();
    }

    private function monitorI() {

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

	if ($add) {
	    beout('USB connected');
	    belg('KWBATTUSBADD' . "\n");
	}
	if ($rm ) {
	    belg('u-sb removed' . "\n");
	    beout('USB removed...');
	    sleep(2);
	    beout('');
	    belg('KWBATTUSBRM' . "\n");
	}

	if (($add || $rm) && $this->standalone) { $this->exit(); }
	$this->close(); 


    }


    public function exit() {

	if (!($this->exiting ?? false)) {
	    $this->exiting = true;
	} else {
	    belg('dup usb e-xiting call.  returning...' . "\n");
	    return;
	}


	belg('usb e xit called' . "\n");
	
	$this->close();

	beout('');
	belg('exiting now......');
	exit(0);
    }

    private function initSignals() {
	pcntl_async_signals(true);
	pcntl_signal(SIGINT , [$this, 'exit']);
	pcntl_signal(SIGTERM, [$this, 'exit']);
    }



}

if (didCLICallMe(__FILE__)) { USBADBCl::monitor(true);  }