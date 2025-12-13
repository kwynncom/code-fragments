<?php

use React\EventLoop\Timer\TimerInterface;
use React\EventLoop\Loop;


require_once('adbLevel.php');

class adbDevicesCl {

    private static mixed $noti;

public static function doit(mixed $noti) {
    self::$noti = $noti;
    self::debounce();
}

private static function debounce() {

    static $debounceTimer = null;
    $loop = Loop::get();

    if ($debounceTimer) {       $loop->cancelTimer($debounceTimer);    } 
    else { self::devs10();    }

    $debounceTimer = $loop->addTimer(3.0, function ()  {
        belg('debounce call');
	self::devs10();
        $debounceTimer = null;
    });
}

private static function devs10() {
    $ret = self::devsActual();

    self::$noti->notify('devices', is_string($ret) ? $ret : 'bool', is_bool($ret) ? $ret : null );
}

private static function devsActual() : bool | string {

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
	    belg ('need perm');
	    return 'perm';
	}

	return true;
    }

    return false;
} // func

} // class