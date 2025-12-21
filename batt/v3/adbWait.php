<?php

require_once('utils.php');

use React\Stream\ReadableResourceStream;
use ReactLineStream\LineStream;
use React\EventLoop\Loop;
use React\ChildProcess\Process;

class adbWaitCl {
    private	     object $lines;
    private mixed  $inputStream;

    public function __construct() {
	$this->init();
    }

    private function init() {
	$c = 'adb wait-for-device 2>&1';
	belg($c, true);
	$process = new Process($c);

	$process->start(Loop::get());

	// Stream stdout in real-time (non-blocking)
	$process->stdout->on('data', function ($chunk) {
	    echo "STDOUT: " . $chunk;
	});

	// Stream stderr in real-time
	$process->stderr->on('data', function ($chunk) {
	    echo "STDERR: " . $chunk;
	});

	// Handle process exit (non-blocking)
	$process->on('exit', function ($exitCode, $termSignal) {
	    if ($termSignal === null) {
		echo "Process exited with code: " . $exitCode . PHP_EOL;
	    } else {
		echo "Process terminated by signal: " . $termSignal . PHP_EOL;
	    }
	});


    }

}

if (didCLICallMe(__FILE__)) new adbWaitCl();