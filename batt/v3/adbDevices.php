<?php

use React\EventLoop\Timer\TimerInterface;
use React\EventLoop\Loop;


require_once('adbLevel.php');

class adbDevicesCl {



public static function doit() {
    static $o;
    if (!$o) $o = new self();

    $o->debounce();
  
}

private function debounce() {
    static $debounceTimer = null;

    if ($debounceTimer) {
        $this->loop->cancelTimer($debounceTimer);
    } else {
	$this->devs10();
    }

    $debounceTimer = $this->loop->addTimer(3.0, function ()  {
        belg('debounce call');
	$this->devs10();
        $debounceTimer = null;
    });

}

private function devs10() {
    $ret = self::devsActual();
    if (!$ret) return;
    adbLevelCl::push(true);
}

private readonly object $loop;

private function __construct() {

    
    $this->loop = Loop::get();

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

	return true;
    }

    return false;
} // func
} // class