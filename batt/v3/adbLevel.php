<?php

require_once('utils.php');

class adbLevelCl {

    private static object $self;

    public static function connTrend(bool $dir, object $noti) {

	static $lat  = 0;
	static $prev;
    
	if (!$dir) {
	    $prev = $dir;
	    return;
	}

	$now = time();

	if (!$prev && $dir && $now - $lat < 20) { 	    return; 	}

	$lat = $now;
	$res = self::sendLevelFromPhoneFile();
	
	$noti->notify('level', 'file', $res >= 0, $res);
	$prev = $res  >= 0 ? true : false;
	
	
    }

    private static function sendLevelFromPhoneFile() : int {
	$c = 'adb shell cat /sys/class/power_supply/battery/capacity';
	belg("$c\n");
	$tlev = self::filtLevel(shell_exec($c));
	if ($tlev === false) { return -1; }
	$level = $tlev; unset($tlev);
	belg('LEVEL *** ' . $level . " ***\n");
	return $level;
    }

    private static function filtLevel(mixed $res) : int | false {

	try {
	    belg('filt string = ' . $res);
	    kwas($res && is_string($res), 'bad res type');
	    $res = trim($res);
	    kwas(is_numeric($res), 'not numeric');
	    kwas(is_string($res), 'not string');
	    $n = strlen($res);
	    kwas($n > 0 && $n <= 3, 'invalid l-evel - string'); unset($n);
	    $i10 = intval  ($res); unset($res);
	    kwas($i10 >= 0 && $i10 <= 100, 'invalid l-evel as int');
	    $level = $i10; unset($i10);
	    belg('returning ' . $level);
	    return $level;
	} catch(Throwable $ex) {
	    $msg = $ex->getMessage();
	    belg('bad level ' . $msg . "\n");
	}

	belg('returning false');
	return false;
    }

}