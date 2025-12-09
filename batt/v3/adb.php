<?php

class adbCl {


    private static function sendLevel() : bool {

	try {
	    $c = 'adb shell cat /sys/class/power_supply/battery/capacity';
	    belg('running adb battery check' . "\n");
	    $res = trim(shell_exec($c));
	    kwas(is_numeric($res), 'not numeric');
	    kwas(is_string($res), 'not string');
	    $n = strlen($res);
	    kwas($n > 0 && $n <= 3, 'invalid l-evel - string'); unset($n);
	    $i10 = intval  ($res); unset($res);
	    kwas($i10 >= 0 && $i10 <= 100, 'invalid l-evel as int');
	    $level = $i10; unset($i10);
	    beout($level);
	    belg('LEVEL *** ' . $level . " ***\n");
	    return true;

	} catch(Throwable $ex) {
	    beout('');
	    $msg = $ex->getMessage();
	    belg('bad level ' . $msg . "\n");
	    beout($msg);
	    return false;
	}

	return false;
	
    }

    public static function doit() : bool {
	self::condSend();
	$ret = self::devices();
	self::condSend($ret);
	return $ret;
    }

    private static function condSend(bool $nv = false) {
	static $i  = 0;
	static $ov = false;

	if ($nv) return;

	if ($i++ === 0) { beout('init'); return; }
	

    }

    private static function devices() : bool {
	
	for ($i=0; $i < 2; $i++) {
	    $c = 'adb devices';
	    belg('running ' . $c . "\n");
	    $shres = shell_exec($c);
	    $pares = self::parseDevices($shres);
	    if ($pares === true)   { return self::sendLevel();    }
	    if ($pares === 'perm') {  self::getPerm(); }
	    else { return false; }
	}

	return false;
	
    }

    private static function getPerm() {
	beout('need permission');
	belg ('need perm');
	$timeout = 20;
	$c = 'timeout --foreground ' . $timeout . ' adb wait-for-device';
	belg($c);
	shell_exec($c);
	belg('wait for devices exited one way or another');
	
    }

    private static function parseDevices(string $s) : bool | string {
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
		return 'perm';
	    }

	    return true;
	}

	return false;
    }
}

