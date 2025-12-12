<?php

require_once('utils.php');
require_once('usb_sh.php');

use React\ChildProcess\Process;
use React\EventLoop\Loop;

class procWaitCl {

private readonly string $cmd;

public function __construct(string $cmd) {
    $this->cmd = $cmd;
    $this->wait();
}

public function sigintHandler(int $signal) {
    echo "\nCaught SIGINT (Ctrl+C) – shutting down gracefully (adb wait-for-devices)…\n";
    // Loop::get()->stop();    
    $this->close('control-C / SIGINT'); 
}

public function __destruct() { $this->close(); }

private function close() {
    Loop::get()->stop();
    // Loop::get()->removeSignal(SIGINT, [$this, 'sigintHandler']);
}

private readonly bool $exiting;
private readonly mixed $input;

private function exit($d1 = '', $d2 = '') {

	if ($this->exiting ?? false) return;
	$this->exiting = true;

	if ($d1 === 0) {
	    belg($this->cmd . ' normal result');
	    // doYourStuffWhenDeviceAppears();
	} else {
	    belg($this->cmd . " exited abnormally (code: $d1)");
	}

	$this->close();
    }

private function wait() {

    $loop = Loop::get();

    // $loop->addSignal(SIGINT, [$this, 'sigintHandler']);

    $process = new Process($this->cmd);
    $process->start();

    belg('running in PHPReact: ' . $this->cmd);

    $process->on('exit', function($a, $b = null) { $this->exit($a, $b); } );
    $process->on('data', function($a, $b = null) { $this->exit($a, $b); } );

    $loop->run(); // your other code (HTTP server, WebSockets, etc.) runs normally here


} // func
} // class


if (didCLICallMe(__FILE__)) {
    $cmd = 'sleep 1 && echo hi';
    new procWaitCl($cmd);
    unset($cmd);
}
