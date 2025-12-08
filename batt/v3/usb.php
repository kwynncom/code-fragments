<?php

require_once('adb.php');


class USBADBCl extends adbCl {

    public  bool|null $usb;
    private int   $timeout;
    private static bool $initV = false;
  

    private function monitorUSB() {

	$c = '';
	if ($this->timeout > 0) $c .= 'timeout ' . $this->timeout . ' ';
	$c .= 'udevadm monitor -s usb';
	$descriptors = [  1 => ['pipe', 'w'], ];

	unset($this->usb);
	echo('proc_open ' .  $c . ' timeout is ' . $this->timeout . "\n");
	$process = proc_open($c, $descriptors, $pipes); unset($c, $descriptors);
	$stdout = $pipes[1]; unset($pipes);

	while ($l = fgets($stdout)) {
	    $add = strpos($l, 'add') !== false;
	    $rm  = strpos($l, 'remove') !== false;
	    if ($add || $rm) {
		break;
	    }
	} unset($l);

	fclose($stdout); unset($stdout);

	proc_terminate($process, SIGTERM);
	proc_close($process); unset($process);

	echo('exiting monitorUSB()' . "\n");

	if ($add) return $this->usb = $add;
	if ($rm ) {
	    $this->usb = $rm;
	    $this->exit();
	}

    }

    public static function getLevel(int $timeout = 0) {
	static $o;

	if (!isset($o)) $o = new self();
	$o->doit($timeout);
	return $o;
    }

    public function exit() {
	echo('usb exit called' . "\n");
	if (($this->usb ?? null) === false) {
	    beout('USB disconnected.  Exiting...');
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


    private function doit(int $timeout) {
	$this->timeout = $timeout;
	if ($this->timeout) $this->monitorUSB();
	$this->setADB((!$this->timeout) ||  ($this->usb ?? false) === true);
    }

}

