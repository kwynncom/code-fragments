<?php

declare(strict_types=1);

require_once('utils.php');
require_once('adb.php');
require_once('/var/kwynn/batt/PRIVATEphones.php');

class battExtCl implements battExtIntf, KWPhonesPRIVATE {

    private function monitor() {

	for($i=0; $i < self::nMaxLoop; $i++) { 
	    belg('checking l-evel. ' . $i . ' of max loop: ' . self::nMaxLoop . "\n");
	    $this->adbo->doit();
	    sleep(2);
	}

	belg('e-xit per normal (for now) max loop after n iterations === ' . $i);
    }

    private readonly object $adbo;

     public function __construct() {
	beout('');
	$this->adbo = new adbCl();
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
