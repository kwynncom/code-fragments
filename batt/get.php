<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');
require_once('parse.php');

class adbCl {

    private  readonly array $snsGetA;
    private  readonly array $props;
    public   readonly array $info;

    public static function get() {
	$o = new self();
	return $o->info;
    }

   private function __construct() {
	try {
	    $this->do10();
	    $tbatta = $this->do40();
	    $this->do90SetAll($tbatta);
	    
	} catch(Throwable $ex12) {
	    $this->doEx($ex12);
	}
   }

    private function do90SetAll(array $a) {
	$ret = [];
	foreach($a as $sn => $batt) {
	    $ret[$sn]['battery'] = $batt;
	    $ret[$sn]['gen'] = $this->props[$sn];
	    $ret[$sn]['Uat'] = time();
	}
	
	$this->info = $ret;
    }

    private function do40() : array {
	kwas(isset($this->snsGetA) && count($this->snsGetA) >= 1, 'no adb devices - inconsistent - err # 051421');

	$ret = [];
	foreach($this->snsGetA as $sn => $sna) {
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
	$props = [];

	foreach($a as $did) {
	    kwas($did && is_string($did), 'non-string for device ID - err # 410449');

	    $sn = $this->getVSN($this->getProps($did, 'ro.serialno'));

	    $isip = $this->isIPv4Loose($did);
	    if ($isip) {
		$key = 'ip';
	    } else {
		kwas($sn === $did, 'failed assuming that device ID is a serial number if not IP - err # 0355176');
		$key = 'sn';
	    }
	    
	    $snsGetA[$sn][$key] = $did;
	  
	}

	$this->snsGetA = $snsGetA;
	$this->props = $this->getProps();
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

