<?php
declare(strict_types=1);

require_once('adbReact.php');

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

    const sendLimit = 5;

    public function bufferedSend(bool $prob) : bool {

	if (!$prob && isset($this->logcato)) unset($this->logcato);

	if ($prob && (time() - $this->Uvalid < self::sendLimit)) {
	    belg('buffered / redudant catlog info; ignoring');
	    return true;
	}
	$prev = $this->levelV;
	$this->levelV = self::sendLevelFromPhoneFile();
	if ($this->levelV !== false) {
	    if ($this->levelV !== $prev) {
		$this->Uvalid = time();
		beout($this->levelV);
	        return true;
	    }
	} else {
	    beout('');
	}
	
	return false;
	
    }

    public function doit() : bool {
	$ret = $this->devices();
	if ($ret === true) { 
	    if (!isset($this->logcato)) $this->logcato = new ADBLogReaderCl([$this, 'bufferedSend']);
	}
	return $ret;
    }


    private function devices() : bool {
	
	for ($i=0; $i < 2; $i++) {
	    $c = 'adb devices';
	    belg('running ' . $c . "\n");
	    $shres = shell_exec($c);
	    belg('finished ' . $c);
	    $pares = self::parseDevices($shres);
	    if ($pares === true)   { return $this->bufferedSend(true);    }
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

