<?php

require_once('utils.php');

use React\Stream\ReadableResourceStream;
use ReactLineStream\LineStream;
use React\EventLoop\Loop;
use React\ChildProcess\Process;

class adbWaitCl {

    private int $n = 0;

    public function __construct($oin) {
	$this->init($oin);
    }

    private function init($oin) {
	$c = 'adb wait-for-device 2>&1';
	belg($c . ' call ' . $this->n++, true);
	$process = new Process($c);
	$process->start(Loop::get());
	$process->on('exit', function ($exitCode) use($oin) {

	    if ($exitCode === 0) {
		$oin->notify('devices', 'found');
	    }

	    if ($this->n > 3) {
		belg('too many wait restarts.  Exiting.');
		return;
	    }

	    $this->init($oin);
	    
	});


    }

}

// if (didCLICallMe(__FILE__)) new adbWaitCl();