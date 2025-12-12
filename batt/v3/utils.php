<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');
require_once('kill.php');

interface battExtIntf {
    const nMaxLoop       = 20;  // PHP_INT_MAX
    const usbTimeoutInit =  5;
    const timeoutSteadyState = 67;
}

class battKillCl {

    const lockf = '/tmp/kwbatt.pid';

    public static function isPrev() : bool {
	return PidFileGuard::isRunning(self::lockf);
    }


    public static function killPrev() {
	$res = self::isPrev();
	if ($res) belg('another process to kill...');
	PidFileGuard::acquire(self::lockf);
    }



}

if (!isset($BEOUTO)) { 
    $BEOUTO = new battLogCl();
}

function beout($s) {
    global $BEOUTO;

    $BEOUTO->put($s);
    
    $t  = '';
    $t .= 'emitting ' . $s . ' ';
    $t .= PHP_EOL;

    echo($t);
    $c = 'busctl --user emit /kwynn/batt com.kwynn IamArbitraryNameButNeeded s ' . '"' . $s . '"';
    shell_exec($c);
}

function belg(string $s) {
    global $BEOUTO;

    $BEOUTO->put($s);
}

function getbeout() : string {
    return $BEOUTO->get();
}

class battLogCl {

    private readonly string $logf;

    private string|int $theV = '';

    public function get() : string {
	return $this->theV;
    }

    public function put($s) {
	static $i = 1;

	$this->theV = $s;

	if (!$s && is_string($s) && strlen(trim($s)) === 0) $s = '(blanking)';

	$t  = '';
	$t .= $i;
	$t .= ' ';
	$t .= date('H:i:s');
	$t .= ' ';
	$t .= $s;
	$this->putA($t);
	$i++;
    }

    public function __construct() {
	$this->initLog();
    }

    private function putA(string $sin) {
	$s = trim($sin) . "\n"; unset($sin);
	
	file_put_contents($this->logf, $s, FILE_APPEND);
	echo($s);
    }

    private function initLog() {
	$f = '/tmp/belg.txt';
	kwas(touch($f), "cannot create / touch $f");
	kwas(chmod($f, 0600), "cannot chmod $f");
	$this->logf = $f;
	$this->put(date('Y-m-d'));
    }

}

