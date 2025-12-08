<?php

class adbCl {

    public  bool $something = false;
    public  string $msg = 'unk';
    public  readonly bool $noPerm;
    public  int $level = -1;

    private function setLevel() {

	try {

	    $c = 'adb shell cat /sys/class/power_supply/battery/capacity';
	    $res = trim(shell_exec($c));
	    kwas(is_numeric($res), 'not numeric');
	    kwas(is_string($res), 'not string');
	    $n = strlen($res);
	    kwas($n > 0 && $n <= 3, 'invalid level - string');
	    $i10 = intval  ($res);
	    kwas($i10 >= 0 && $i10 <= 100, 'invalid level as int');
	    $this->level = $i10;

	} catch(Throwable $ex) {
	    $this->msg = $ex->getMessage();
	    $this->level = -1;
	}
	
    }

    protected function setADB(bool $doTO) : object {
	$this->msg = $this->devices($doTO);
	if (!isset($this->noPerm)) $this->noPerm = false;
	return $this;
    }

    private function devices($doTO) : string {

	$n = 8;
	$sleep = 1;
	$ret = 'unk';

	for ($i = 0; $i < $n; $i++) {
	    $ret = $this->devicesLoop();
	    if ($this->something || (!$doTO)) break;
	    if ($i + 1 < $n) {
		echo('adb.php devices() sleep for ' . $sleep . ' i ' . $i . "\n");
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

