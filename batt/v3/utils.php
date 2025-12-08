<?php

require_once('/opt/kwynn/kwutils.php');

interface battExtIntf {
    const nMaxLoop       = 15;  // PHP_INT_MAX
    const usbTimeoutInit = 5;
}


function beout(string $s) {
    battLogCl::put($s);
    $c = 'busctl --user emit /kwynn/batt com.kwynn IamArbitraryNameButNeeded s ' . '"' . $s . '"';
    shell_exec($c);
}

function belg(string $s) {
    battLogCl::put($s);
}

class battLogCl {

    private readonly string $logf;

    public static function put(string|int $s) {
	static $o = false;
	if (!$o) $o = new self();
	$o->putI($s);
    }

    private function putI($s) {
	static $i = 1;

	if (!$s) $s = '(blanking)';

	$t  = '';
	$t .= $i;
	$t .= ' ';
	$t .= date('H:i:s');
	$t .= ' ';
	$t .= $s;
	$this->putA($t);
	$i++;
    }

    private function __construct() {
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
	$this->putI(date('Y-m-d'));
    }

}

