<?php

class adbCl {

    public  bool $something = false;
    public  readonly string $msg;
    public  readonly bool $noPerm;
    public  int $level = -1;

    public static function getDevices() : object {
	$o = new self();
	return $o;
    }

    private function setLevel() {
	$c = 'adb shell cat /sys/class/power_supply/battery/capacity';
	$res = trim(shell_exec($c));
	if (!is_numeric($res)) return;
	$i10 = intval($res);
	if ($i10 < 0 || $i10 > 100) return;
	$this->level = $i10;
	
    }

    private function __construct() {
	$this->msg = $this->devices();
	if (!isset($this->noPerm)) $this->noPerm = false;
    }

    private function devices() : string {

	$n = 5;
	$sleep = 1;
	$ret = 'unk';

	for ($i = 0; $i < $n; $i++) {
	    $ret = $this->devicesLoop();
	    if ($this->something) break;
	    if ($i + 1 < $n) {
		echo('sleep ' . $sleep . "\n");
		sleep($sleep);
	    }
	}

	return $ret;
    }

    private function devicesLoop() : string {
	$res = shell_exec('adb devices');
	return $this->parseDevices($res);
    }

    private function parseDevices(string $s) : string {
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
	    
	    $this->something = true;
	    echo($l . "\n");
	    $k = 'no permissions';
	    if (strpos($l, $k) !== false) {
		$this->noPerm = true;
		return $k;
	    }

	    $this->setLevel();

	}

	return $this->something ? 'something' : 'nothing';
    }
}

