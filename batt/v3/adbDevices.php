<?php

// use React\EventLoop\Timer\TimerInterface;
use React\EventLoop\Loop;

require_once('adbLevel.php');
require_once('/var/kwynn/batt/PRIVATEphones.php');

class adbDevicesCl implements KWPhonesPRIVATE {

    const cmd = shCmdCl::advicmdConst;
    const needPerms = ['no permissions', 'unauthorized'];


    private static mixed $cento;

    private static int $iatts = 0;

  

    private static function slowReinitLoop() : bool {

	static $sleep = 5;

	if (++self::$iatts > 3) {
	    belg(self::$iatts . ' adb devices init attempts.  Sleeping for ' . $sleep);
	    if ($sleep) sleep($sleep);

	    return true;
	}

	return false;
    }

public static function ok() {
    self::$iatts = 0;
}

public static function doit(mixed $cento) {
    self::$cento = $cento;
    if (!self::slowReinitLoop()) { 
		self::debounce(); 

    }
    else { self::devs10(); }
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
    $send = 'unkdev';

    if (is_string($ret)) $send = $ret;
    if ($ret === true)   $send = 'found';
    if ($ret === false)  $send = 'nothing';

    self::$cento->notify('devices', $send);
}

private static function devsActual() : bool | string {

    $s = self::$cento->doShCmd(self::cmd);

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

	$l = self::hideSerials($l);
	belg($l . "\n");

	if (self::needPerms($l)) { return 'perm'; }

	return true;
    }

    return false;
} // func

private static function hideSerials(string $sin) : string {
    foreach(KWPhonesPRIVATE::list as $r) {
	$sin = str_replace($r['serial'], $r['alias'], $sin);
    }

    return $sin;

}

private static function needPerms(string $l) : bool {

    foreach(self::needPerms as $k) {
	if (strpos($l, $k) !== false) {
	    belg ('need perm: ' . $l);
	    return true;
	}
    }

    return false;
} // func

} // class