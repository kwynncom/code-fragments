<?php
declare(strict_types=1);

require_once('adbLog.php');
require_once('wait.php');
require_once('waitUSB.php');
require_Once('adbLevel.php');

class adbCl {

    public function __construct() {
	new ADBLogReaderCl(['adbLevelCl', 'push']);
    }

    public function doit() {

	for($i=0; $i < 30; $i++) {

	    $this->doit20();

	    $sleep = 5;
	    belg('adb doit sleep ' . $sleep);
	    if ($sleep) { 
		sleep($sleep);
	    }
	}

    }

    private function doit20() : bool {
	$ret = $this->devices();
	belg('adb d-oit ret = ' . ($ret ? 'true' : 'false'));
	if ($ret === true) { 
	    adbLevelCl::push(true);
	} else {
	    usbWaitCl::wait([$this, 'devices']);
	    
	}
	return $ret;
    }


    public function devices() : bool {
	
	for ($i=0; $i < 2; $i++) {
	    $ret = self::checkDevices();
	    $this->push($ret);    
	    if ($ret) return $ret;
	}

	return false;
    }

    private static function checkDevices() : bool  {

	$c = 'adb devices 2>&1';
	belg('running ' . $c . "\n");
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