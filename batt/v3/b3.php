<?php

declare(strict_types=1);

use React\EventLoop\Loop;

require_once('utils.php');
require_once('adbLevel.php');
require_once('adbLog.php');

class battExtCl {

    private function monitor() {
	new ADBLogReaderCl(['adbLevelCl', 'push']);
    }

    private readonly object $adbo;

     public function __construct() {

	global $PHPREACTLOOPGL;

	beout('');
	$this->initSignals();
	battKillCl::killPrev();
	$this->monitor();
	Loop::run();
    }

    public function __destruct() { $this->exit();  }

    private function initSignals() {
	pcntl_async_signals(true);
	pcntl_signal(SIGINT , [$this, 'exit']);
	pcntl_signal(SIGTERM, [$this, 'exit']);
    }

    public function exit() {
	beout('');
	belg('b3 e-xit called' . "\n");
	Loop::stop();
	exit(0);
    }
}

new battExtCl();
