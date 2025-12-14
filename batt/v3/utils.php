<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');

require_once('kill.php');

interface battExtIntf {
    const nMaxLoop       = 20;  // PHP_INT_MAX
    const usbTimeoutInit =  5;
    const timeoutSteadyState = 67;

    const msgSeek = 'seeking USB'; // 3 messages not used at the moment, I don't think.
    const msgRm   = 'USB disconnect...';
    const msgAdd  = 'USB connected...';

}

class battKillCl {

    const lockf = '/tmp/kwtt_2025_1213_01.pid';

    public static function isPrev() : bool {
	return PidFileGuard::isRunning(self::lockf);
    }


    public static function killPrev() {
	$res = self::isPrev();
	if ($res) belg('another process to kill...');
	PidFileGuard::acquire(self::lockf);
    }



}

$BEOUTO;

if (!isset($BEOUTO)) { 
    $BEOUTO = new battLogCl();
}

function beout($s) {
    global $BEOUTO;

    $BEOUTO->put($s, true);
    $c = 'busctl --user emit /kwynn/batt com.kwynn IamArbitraryNameButNeeded s ' . '"' . $s . '"';
    shell_exec($c);
}

function belg(string $s, bool $star = false) {
    global $BEOUTO;

    $BEOUTO->put($s, false, $star);
}

function getbeout() : string|int {
    global $BEOUTO;
    return $BEOUTO->get();
}



class battLogCl {

    private readonly string $logf;

    private static string|int $current = '(init)';

    public static function get() : string|int {
	return self::$current;
    }

    public function put(string|int $s, bool $emitting = false, bool $star = false) {
	static $i = 1;
	static $c1s = -1;
	static $lnothb = 0;

	$pnl = false;

	$c1 = ($c1s < 65) && !$star && !$emitting && is_string($s) && (strlen($s) === 1);

	if ($c1) {
	    if (microtime(true) - $lnothb < 2.0) return;
	    $c1s++;
	}
	else {
	    $lnothb = microtime(true);
	    if ($c1s >= 0) $pnl = true;
	    $c1s = -1;
	}

	if ($emitting) self::$current = $s;

	if (!$s && is_string($s) && strlen(trim($s)) === 0) $s = '(blanking)';

	$t  = '';
	if ($c1s <= 0) {
	    $t .= $i;
	    $t .= ' ';
	    $t .= date('H:i:s');
	    $t .= ' ';
	    if ($star || $emitting) $t .= '********* ';
	    if ($emitting) $t .= 'emitting ';
	}
	$t .= $s;

	$t = ($pnl ? "\n" : '') . trim($t) . ($c1s < 0 ? "\n" : '');
	$this->putA($t);
	$i++;
    }

    public function __construct() {
	$this->initLog();
    }

    private function putA(string $s) {
	file_put_contents($this->logf, $s, FILE_APPEND);
	echo($s);

	if (strpos($s, "\n") === false) {
	    // fflush($this->logf);
	    fflush(STDOUT);
	}
    }

    private function initLog() {
	$f = '/tmp/belg.txt';
	kwas(touch($f), "cannot create / touch $f");
	kwas(chmod($f, 0600), "cannot chmod $f");
	$this->logf = $f;
	$this->put(date('Y-m-d'));
    }



}

