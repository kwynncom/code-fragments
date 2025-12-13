<?php

require_once('utils.php');
require_once('/var/kwynn/batt/PRIVATEphones.php');

class usbMonitorCl {

    private static function lsusb() : bool {

	$b = microtime(true);
	$s = shell_exec('timeout -k 0.1 0.15 lsusb');
	$e = microtime(true);
	belg('lsusb took ' . sprintf('%0.3f', $e - $b) . 's');
	if (!$s || !is_string($s)) return false;

	foreach(KWPhonesPRIVATE::list as $r) {
	    if (strpos($s, $r['vidpid']) !== false) {
		belg($r);
		return true;
	    }
	}

	return false;
    }
}