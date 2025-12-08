<?php

require_once('/opt/kwynn/kwutils.php');
require_once('adb.php');


class battUDevAdmCl {
    public function __construct() {

	$this->initSignals();

	$o = $this->initWatch();
	if (!$o->something) return;
	$this->monitor();
    }

    public function __destruct() {
	$this->exit();
    }

    public function exit() {
	self::bout('');
	exit(0);
    }

    private function initSignals() {
	pcntl_async_signals(true);
	pcntl_signal(SIGINT , [$this, 'exit']);
	pcntl_signal(SIGTERM, [$this, 'exit']);
    }

    private function monitor() {
	for($i=0; $i < 500; $i++) {
	    $o = adbCl::getDevices();
	    if ($o->level < 0) {
		self::bout('lost connection');
	    } else  self::bout($o->level); // . ' at ' . date('H:i'));
	    sleep(63);
	}

	self::bout('exit per normal (for now) max loop');
    }

    private function initWatch() : object {
	
	self::bout('init');

	$o = adbCl::getDevices();
	if ($o->something) {
	    self::bout($o->msg);
	    return $o;
	}

	
	self::bout('seeking');

	$command = 'udevadm monitor -s usb';
	$descriptors = [  1 => ['pipe', 'w'], ];
	$process = proc_open($command, $descriptors, $pipes); unset($command, $descriptors);
	$stdout = $pipes[1]; unset($pipes);

	self::bout('waiting');

	while ($l = fgets($stdout)) {		    // do NOT want to match " unbind "
	    if ((strpos($l, 'add') !== false) || (strpos($l, ' bind') !== false)) {
		self::bout('USB connected');
		$o = adbCl::getDevices();
		self::bout($o->msg);
		if ($o->noPerm) $o = $this->seekPerm();
		break;
	    }
	} unset($l);

	fclose($stdout); unset($stdout);

	proc_terminate($process, SIGTERM);
	proc_close($process); unset($process);

	return $o;
    }

    private function seekPerm() : object {
	for ($i=0; $i < 45; $i++) {
	    $o = adbCl::getDevices();
	    if ($o->noPerm === false) break;
	    sleep(1);
	}

	if (isset($o)) self::bout($o->msg);

	return $o;
    }

    private static function bout(string $s) {
	echo($s . "\n");
	$c = 'busctl --user emit /kwynn/batt com.kwynn IamArbitraryNameButNeeded s ' . '"' . $s . '"';
	shell_exec($c);

    }

}

new battUDevAdmCl();
