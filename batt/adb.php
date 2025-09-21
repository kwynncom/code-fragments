<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');


class adbBatt {
   public function __construct() {
	try {
	    $this->do10();
	} catch(Throwable $ex12) {
	    $this->doEx($ex12);
	}
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
	foreach($a as $s) {
	    kwas($s && is_string($s), 'non-string for device ID - err # 410449');
	    if ($this->isIPv4Loose($s)) {
		$sn = trim(shell_exec('adb -s ' . $s . ' shell getprop ro.serialno'));
		kwas($sn && is_string($sn) && strlen($sn) > 4, 'bad serial number.  err # 440454');
		kwas(preg_match('/^\S+$/', $sn), 'spaces in serial number err # 450455');
	    }
	}
    }

    private function isIPv4Loose(string $s) : bool {
	$is = preg_match('/^(\d+\.){3}\d+\:\d+$/', $s, $m);
	return $is ? true : false;
    }
}

new adbBatt();