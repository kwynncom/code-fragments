<?php

require_once('/opt/kwynn/kwutils.php');

class XRatiosODSCl {

    const from = '/var/kwynn/X/ratios.ods';
    const to   = '/tmp/';

    public readonly array $xids;

    public function __construct() {
	$a = $this->getA();
	$this->do20($a); unset($a);
	return;
    }

    private function do20(array $a) {
	$ret = [];
	foreach($a as $r) {
	    $s = $this->do30(trim($r));
	    if ($s) $ret[] = $s;
	}

	$this->xids = $ret;
    }

    private function do30(string $r) : string {
	if (!$r) return '';
	$re = '/https:\/\/x\.com\/.+\/(\d+)$/';
	preg_match($re, $r, $ma);
	return $ma[1] ?? '';
    }

    private function getA() : array {
	$newFrom = $this->copy();
	$Ufrom = filemtime($newFrom);
	$c = ' soffice --headless --convert-to csv ' . $newFrom . ' --outdir ' . self::to;
	$res = shell_exec($c);
	unlink($newFrom);

	// only correct if not overwriting
	// kwas(!$res, "ODS conversion should result in silence but got $res");
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