<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');

class adbBatt {

    private readonly array $sns;

   public function __construct() {
	try {
	    $this->do10();
	    $this->do40();
	} catch(Throwable $ex12) {
	    $this->doEx($ex12);
	}
   }

    private function do40() {
	kwas(isset($this->sns) && count($this->sns) >= 1, 'no adb devices - inconsistent - err # 051421');
	foreach($this->sns as $sn => $sna) {
	    $this->do50($sna, $sn);
	}
    }

    private function do50(array $a, string $sn) {

	$res = [];

	foreach(['sn', 'ip'] as $type) {
	    if (!isset($a[$type])) continue; 
	    $id =      $a[$type];
	    break;
	}

	kwas(isset($id), 'no ID to call ADB - err # 051736');

	$this->do60($id);

	return;
    }
    
    private function do60(string $did) {
	$c = 'adb -s ' . $did . ' shell dumpsys battery';
	$res = shell_exec($c);
	return;
    }

    private function doEx(Throwable $exin) {
	echo($exin->getMessage() . "\n");
	die(-19);
    }

   private function do10() {
	$t = shell_exec('adb devices');
	$a = explode("\n", $t);
	kwas($a && is_array($a) && trim($a[0]) === 'List of devices attached' && count($a) > 1, 
	    'no adb devices - first pass');
	unset($a);
	$this->do20($t);
	return;
   }

   private function do20(string $t)  {
	preg_match_all('/^(.+?)\s+device$/m', $t, $matches); unset($t);
	$result = $matches[1]; unset($matches);
	kwas($result && is_array($result) && count($result) >= 1, 'no adb devices - 2nd pass');
	$this->do30($result);
   }

    private function do30(array $a) {

	$sns = [];

	foreach($a as $s) {
	    kwas($s && is_string($s), 'non-string for device ID - err # 410449');
	    if ($this->isIPv4Loose($s)) {
		$sn = $this->getVSN(shell_exec('adb -s ' . $s . ' shell getprop ro.serialno'));
		$sns[$sn]['ip'] = $s;
	    } else {
		$sn = $this->getVSN($s);
		$sns[$sn]['sn'] = $sn;
	    }
	}

	$this->sns = $sns;
	return;
    }

    private function getVSN(string $sn) : string {

	$sn = trim($sn);

	try {
	    kwas($sn && is_string($sn) && strlen($sn) > 4, 'bad serial number.  err # 440454');
	    kwas(preg_match('/^\S+$/', $sn), 'spaces in serial number err # 450455');
	} catch(Throwable $ex54) { throw $ex54; }
	return $sn;
    }

    private function isIPv4Loose(string $s) : bool {
	$is = preg_match('/^(\d+\.){3}\d+\:\d+$/', $s, $m);
	return $is ? true : false;
    }
}

new adbBatt();