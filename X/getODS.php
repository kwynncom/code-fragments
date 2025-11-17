<?php

require_once('/opt/kwynn/kwutils.php');

class XRatiosODSCl {

    const from = '/var/kwynn/X/ratios.ods';
    const to   = '/tmp/';

    public function __construct() {
	$a = $this->getA();
	$this->do20($a); unset($a);
    }

    private function do20(array $a) {
	foreach($a as $r) $this->do30(trim($r));
    }

    private function do30(string $r) {
	if (!$r) return;
	$re = '/https:\/\/x\.com\/.+\/(\d+)$/';
	preg_match($re, $r, $ma);
	if (!$ma) return;
	return;
    }

    private function getA() : array {
	$newFrom = $this->copy();
	$Ufrom = filemtime($newFrom);
	$c = ' soffice --headless --convert-to csv ' . $newFrom . ' --outdir ' . self::to;
	$res = shell_exec($c);
	unlink($newFrom);
	kwas(!$res, "ODS conversion should result in silence but got $res");
	$csv = self::to . basename($newFrom, '.ods') . '.csv';
	kwas(is_readable($csv) && filesize($csv) > 30 && filemtime($csv) >= $Ufrom, 'problem with CSV (err # 201022)');
	$a =  str_getcsv(file_get_contents($csv));
	kwas($a && is_array($a) && count($a) >= 1, "bad csv array from $csv");
	return $a;
    }

    private function copy() : string {
	$pi = pathinfo(self::from, PATHINFO_DIRNAME) .  '' . basename(self::from, '.ods');
	$to = $pi . '-' . (iscli() ? 'cli' : 'www') . '.ods';
	kwas(copy     (self::from, $to), 'failed copy ' . self::from . ' to ' . $to);
	return $to;
    }	  
}

if (didCLICallMe(__FILE__)) new XRatiosODSCl();