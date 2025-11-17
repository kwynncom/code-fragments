<?php

require_once('/opt/kwynn/kwutils.php');

class XRatiosODSCl {

    const from = '/var/kwynn/X/ratios.ods';
    const to   = '/tmp/';

    public function __construct() {
	$this->do10();
    }

    private function do10() {
	$newFrom = $this->copy();
	$Ufrom = filemtime($newFrom);
	$c = ' soffice --headless --convert-to csv ' . $newFrom . ' --outdir ' . self::to;
	$res = shell_exec($c);
	unlink($newFrom);
	kwas(!$res, "ODS conversion should result in silence but got $res");
	$csv = $this->getCSVName($newFrom);
	kwas(is_readable($csv) && filesize($csv) > 30 && filemtime($csv) >= $Ufrom, 'problem with CSV (err # 201022)');
	return;
	
	
	
    }

    private function getCSVName(string $ods) {
	$pi = pathinfo($ods, PATHINFO_DIRNAME) .  '' . basename($ods, '.ods');
	$n  = $pi . '.csv';
	return $n;
    }

    private function copy() : string {

	$pi = pathinfo(self::from, PATHINFO_DIRNAME) .  '' . basename(self::from, '.ods');
	$to = $pi . '-' . (iscli() ? 'cli' : 'www') . '.ods';
	kwas(copy     (self::from, $to), 'failed copy ' . self::from . ' to ' . $to);
	return $to;
	
	
    }	  


}

if (didCLICallMe(__FILE__)) new XRatiosODSCl();