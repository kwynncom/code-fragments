<?php

class batteryLinesCl {
    public static function line(string $s, object $noti) {
	// belg('att line match ' . $s);
	preg_match('/level:(\d{1,3}),/', $s, $m);
	self::filt10($m, $noti);

        
	preg_match('/ batteryLevel=(\d{1,3}),/', $s, $m);
	self::filt10($m, $noti);
    }	

    private static function filt10($m, object $noti) {
	// if ($m[0] ?? false) belg('match = ' . $m[0]);
	if (isset($m[1])) {
	    $tlev = adbLevelCl::filt($m[1]);
	    if ($tlev === false) return;
	    $noti->levelFromADBLog($tlev);
	}
    }

}
