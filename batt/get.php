<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');
require_once('parse.php');

class adbBattCl {

    private readonly array $snsGetA;
    private readonly array $battA;
    public  readonly array $dat;
    public  readonly array $props;

    public static function get() {
	$o = new self();
	return $o->dat;
    }

   private function __construct() {
	try {
	    $this->do10();
	    $this->dat = $this->do40();
	} catch(Throwable $ex12) {
	    $this->doEx($ex12);
	}
   }

    private function do90ID() {
	
    }

    private function do40() : array {
	kwas(isset($this->snsGetA) && count($this->snsGetA) >= 1, 'no adb devices - inconsistent - err # 051421');

	$ret = [];
	foreach($this->snsGetA as $sn => $sna) {
	    $b = $this->do90ID();
	    $a = $this->do50($sna, $sn); // battery stuff
	    $ret[$sn] = adbBattParseCl::get($a);
	}
	return $ret;
    }

    private function do50(array $a, string $sn) : array {

	$res = [];

	foreach(['sn', 'ip'] as $type) {
	    if (!isset($a[$type])) continue; 
	    $id =      $a[$type];
	    break;
	}

	kwas(isset($id), 'no ID to call ADB - err # 051736');

	return $this->do60($id);

	
    }
    
    private function do60(string $did) : array {
	$res = $this->adb('dumpsys battery', $did);
	kwas($res && is_string($res) && strlen($res) > 150, 'bad adb batt result - err # 052847');
	$a = $this->do70($res);
	return $a;
    }

    private function do70(string $b) : array {
	$lines = preg_split("/\n/", $b); kwas($lines && is_array($lines) && count($lines) >= 14, 
					    'bad array-parsed batt - err # 053054');

	$res = [];
	foreach($lines as $line) {
	    $ta = $this->do80($line);
	    $res[key($ta)] = reset($ta);
	}

	return $res;
    }

    private function do80(string $line) {
	$parts = explode(':', trim($line), 2);
	kwas (count($parts) === 2, 'bad adb batt line parse - err # 670539');
        $key = trim($parts[0]);
        $result[$key] = trim($parts[1]);
	return $result;
    }

    private function doEx(Throwable $exin) {
	echo($exin->getMessage() . "\n");
	die(-19);
    }

    private function adb(string $subCmd, string $screen = '') : string {

	$isd = $subCmd === 'devices';

	$c  = '';
	$c .= 'adb ';
	if ($isd) $c .= $subCmd;
	if ($screen) $c .= '-s ' . $screen . ' ';
	if (!$isd) $c .= 'shell ' . $subCmd;
	$res = trim(shell_exec($c));
	if (!$res) $res = '';
	return $res;
    }

   private function do10() {
	$t = $this->adb('devices');
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

    private function parseGetProp(string $input) {
	$result = [];
	$lines = explode("\n", trim($input));

	foreach ($lines as $line) {
	    // Match pattern: [key]: [value]
	    if (preg_match('/^\[(.+?)\]\s*:\s*\[(.+?)\]$/', $line, $matches)) {
		$key = $matches[1];
		$value = $matches[2];
		$result[$key] = $value;
	    }
	}

	return $result;
    }

    private function getProps(string $did = '', string $prop = '') : string | array {
	static $props;

	if (!$props) $props = [];

	if (!$did) return $props;

	if (isset ($props[$did])) {
	    if ($prop) return $props[$did][$prop];
	    return $props[$did];
	}

	$s = $this->adb('getprop', $did);
	$a = $this->parseGetProp($s);
	$sn = $this->getVSN($a['ro.serialno']);
	$props[$sn] = $a;

	if ($prop) return $a[$prop];
	return $a;
    }

    private function do30(array $a) {

	$snsGetA = [];

	foreach($a as $s) {
	    kwas($s && is_string($s), 'non-string for device ID - err # 410449');
	    $isip = $this->isIPv4Loose($s);
	    if ($isip) {
		$sn = $this->getVSN($this->getProps($s, 'ro.serialno'));
		$snsGetA[$sn]['ip'] = $s;
	    } else {
		$sn = $this->getVSN($s);
		$snsGetA[$sn]['sn'] = $sn;
	    }
	}

	$this->snsGetA = $snsGetA;
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

