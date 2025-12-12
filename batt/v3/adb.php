<?php
declare(strict_types=1);

require_once('adbLog.php');
require_once('wait.php');
require_once('waitUSB.php');

class adbCl {

    private object $logcato;

    private int|false $levelV = false;

    private static function sendLevelFromPhoneFile() : int | false {
	$c = 'adb shell cat /sys/class/power_supply/battery/capacity';
	belg('running adb battery check' . "\n");
	$tlev = self::filtLevel(shell_exec($c));
	if ($tlev === false) return false;
	$level = $tlev; unset($tlev);
	belg('LEVEL *** ' . $level . " ***\n");
	return $level;
    }


    private int $Uvalid = 0;

    const sendLimitS = 20;

    public function bufferedSend(bool $prob) : bool {

	if (!$prob) {
	    unset($this->logcato);
	    $this->Uvalid = 0;
	    $this->levelV = false;
	    beout('');
	    return false;
	}

	if (($prob && $this->levelV !== false) && (time() - $this->Uvalid < self::sendLimitS)) {
	    belg('buffered / redudant catlog info; ignoring');
	    return true;
	}

	// $prev = $this->levelV;
	$this->levelV = self::sendLevelFromPhoneFile();
	if ($this->levelV !== false) {

	    $this->Uvalid = time();
	    beout($this->levelV);
	    return true;

	} else {
	    beout('');
	}
	
	return false;
	
    }

    public function doit() {

	for($i=0; $i < 30; $i++) {

	    if (isset($this->logcato) && (time() - $this->Uvalid < 20)) {
		belg('adb log object defined and thus running; Uvalid recent.  Happy.');
	    } else $this->doit20();

	    $sleep = 10;
	    belg('adb doit sleep ' . $sleep);
	    sleep($sleep);
	}

    }

    private function doit20() : bool {
	$ret = $this->devices();
	belg('adb d-oit ret = ' . ($ret ? 'true' : 'false'));
	if ($ret === true) { 
	    if (!isset($this->logcato)) $this->logcato = new ADBLogReaderCl([$this, 'bufferedSend']);
	} else {
	    usbWaitCl::wait([$this, 'devices']);
	    
	}
	return $ret;
    }


    public function devices() : bool {
	
	for ($i=0; $i < 2; $i++) {
	    $c = 'adb devices 2>&1';
	    belg('running ' . $c . "\n");
	    $shres = shell_exec($c);
	    belg('finished ' . $c);
	    $pares = self::parseDevices($shres);
	    if ($pares === true)   { return $this->bufferedSend(true);    }
	    if ($pares === 'perm') {  self::waitForDevice(true); }
	    else { return false; }
	}

	return false;
	
    }

    public function waitForDevice(bool $needPerm = false) {
	if ($needPerm) { 
	    beout('need permission');
	    belg ('need perm');
	}

	new ADBLogReaderCl([$this, 'bufferedSend']);
	
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
	    beout('');
	    $msg = $ex->getMessage();
	    belg('bad level ' . $msg . "\n");
	}

	belg('returning false');
	return false;
    }

}

