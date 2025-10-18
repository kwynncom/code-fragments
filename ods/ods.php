<?php

require_once('/opt/kwynn/kwutils.php');

class odsFirstSheetCl {
    const source = '/var/kwynn/hours/';
    const marker = 'autoKw18';
    const fs = ['marker', 'start', 'hours', 'permonth', 'rate'];
    const dperm = 30.416667;
    

    private function do40(array $a) {
	if (!$a) return;

	$now = time();

	$s = $now - strtotime($a[1]);
	$hus = number_format($s);
	$elapM = ($s) / (self::dperm * DAY_S);
	$paid  = $elapM * $a[3];
	$dph   = $paid /  $a[2];
	$worked = $a[4] * $a[2];
	$dahead  = $worked - $paid;
	$hrahead = $dahead / $a[4];
	$targdpd = $a[3] / self::dperm;
	$targhpd = $targdpd / $a[4];
	$daysahead = $dahead / $targdpd;
	$Uearnto = roint($now + $daysahead * DAY_S);
	$earntohu = date('r', $Uearnto);
	
	return;
    }


    private function findMarker(array $a) : array {
	foreach($a as $i => $c) {
	    if (trim($c) !== self::marker) continue;
	    $ret = array_slice($a, $i);
	    return $ret;
	}
	return [];
    }

    public function __construct() {
	$this->do10();
    }

    private function do10() {
	$fs = glob(self::source . '*.ods');
	foreach($fs as $f) {
	    $this->do20 ($f);
	    $this->parse30($f);
	}
	return;
    }



    private function parse30(string $f) {
	$csv = $this->otoc($f);
	$t = file_get_contents($csv);
	$a = str_getcsv($t);
	$this->do40($this->findMarker($a));
	return;
    }

    private function otoc(string $f) {
	return str_replace('.ods', '.csv', $f);
    }

    private function already(string $f) : bool {
	$csv = $this->otoc($f);
	if (filemtime($csv) >= filemtime($f)) return true;
	else return false;
    }

    private function do20(string $f) {

	if ($this->already($f)) return;

	$c = 'soffice --headless --convert-to csv ' . $f . ' --outdir ' . self::source;
	$res = shell_exec($c);

// "convert /var/kwynn/hours/blah.ods as a Calc document -> /var/kwynn/hours/blah.csv using 
// filter : Text - txt - csv (StarCalc) "

	return;

    }
}

if (didCLICallMe(__FILE__)) new odsFirstSheetCl();



