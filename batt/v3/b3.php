<?php

declare(strict_types=1);

require_once('utils.php');
require_once('adbLevel.php');
require_once('adbLog.php');

class battExtCl {

    private function monitor() {
	new ADBLogReaderCl(['adbLevelCl', 'push']);
    }

    private readonly object $adbo;

     public function __construct() {
	beout('');
	$this->initSignals();
	battKillCl::killPrev();
	$this->monitor();
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
	exit(0);
    }
}

new battExtCl();
