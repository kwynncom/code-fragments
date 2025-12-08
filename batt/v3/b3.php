<?php

require_once('/opt/kwynn/kwutils.php');
require_once('usb.php');


class battExtCl {
    public function __construct() {
	self::bout('init');
	$this->initSignals();
	$this->monitor();
    }

    public function __destruct() { $this->exit();  }

    public function exit() {
	self::bout('');
	echo('Exiting now.' . "\n");
	exit(0);
    }

    private function initSignals() {
	pcntl_async_signals(true);
	pcntl_signal(SIGINT , [$this, 'exit']);
	pcntl_signal(SIGTERM, [$this, 'exit']);
    }

    private function monitor() {
	for($i=0; $i < 5; $i++) {
	    $o = USBADBCl::getLevel($i === 0 ? 0 : 10);

	    if (($o->usb ?? null) === false) {
		self::bout('USB disconnect.  Exiting...');
		sleep(3);
		$this->exit();
	    }


	    if ($o->level < 0) {
		if ($o->noPerm) $this->seekPerm();
		self::bout('lost connection');
	    } else  self::bout($o->level);



	    $n = 3;
	    echo('sleep outer ' . $n . "\n");
	    sleep($n);
	}

	self::bout('exit per normal (for now) max loop');
    }


    private function seekPerm() : object {
	for ($i=0; $i < 45; $i++) {
	    $o = adbCl::getLevel();
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

new battExtCl();
