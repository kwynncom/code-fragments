<?php

class adbCl {

    public  bool $something = false;
    public  string $msg = 'unk';
    public  bool $noPerm;
    public  int $level = -1;
    public  bool $valid = false; 

    private function setLevel() {

	try {

	    $c = 'adb shell cat /sys/class/power_supply/battery/capacity';
	    echo('running adb battery check' . "\n");
	    $res = trim(shell_exec($c));
	    kwas(is_numeric($res), 'not numeric');
	    kwas(is_string($res), 'not string');
	    $n = strlen($res);
	    kwas($n > 0 && $n <= 3, 'invalid l-evel - string');
	    $i10 = intval  ($res);
	    kwas($i10 >= 0 && $i10 <= 100, 'invalid l-evel as int');
	    $this->level = $i10;
	    $this->valid = true;

	} catch(Throwable $ex) {
	    $this->msg = $ex->getMessage();
	    $this->level = -1;
	    $this->valid = false;
	}
	
    }

    protected function setADB() {
	$this->noPerm = false;
	$this->msg = $this->devices();
    }

    private function devices() : string {
	$c = 'adb devices';
	echo('running ' . $c . "\n");
	$res = shell_exec($c);
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

	    $this->noPerm = false;

	    $this->setLevel();

	}

	return $this->something ? 'something' : 'nothing';
    }
}

