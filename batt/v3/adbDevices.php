<?php

use React\EventLoop\Loop;
use React\EventLoop\Timer\TimerInterface;

require_once('adbLevel.php');

class adbDevicesCl {



public static function doit() {
    static $o;
    if (!$o) $o = new self();

    $o->debounce();
    // self::devsActual(); 
}

private function debounce() {
    $debounceTimer = null;

    if ($debounceTimer) {
        $this->loop->cancelTimer($debounceTimer);
    } else {
	self::devsActual();
    }

    $debounceTimer = $this->loop->addTimer(3.0, function ()  {
        echo "Performing debounced action now!\n";
	self::devsActual();
        $debounceTimer = null;
    });

    echo "Request received – debouncing...\n";
}

private readonly object $loop;

private function __construct() {
    $this->loop = Loop::get();
    $this->loop->run();
}

private static function devsActual() : bool  {

    $c = 'adb devices 2>&1';
    belg($c . "\n");
    $s = shell_exec($c);
    belg('finished ' . $c);


    $a = explode("\n", $s); unset($s);
    $dline = false;
    foreach($a as $rawl) {
	$l = trim($rawl); unset($rawl);
	if ((!$dline) && ($l === 'List of devices attached')) {
	      $dline = true;
	      continue; 
	}
	if (!$dline) continue;
	if (!$l) continue;

	belg($l . "\n");
	$k = 'no permissions';
	if (strpos($l, $k) !== false) {
	    beout('need permission');
	    belg ('need perm');
	    return false;
	}


	adbLevelCl::push(true);
	return true;
    }

    return false;
} // func
} // class