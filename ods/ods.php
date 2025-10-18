<?php

require_once('/opt/kwynn/kwutils.php');

class odsFirstSheetCl {
    const source = '/var/kwynn/hours/';
    const marker = 'autoKw18';
    const fs = ['marker', 'start', 'hours', 'permonth', 'rate']; // just a reminder
    const dperm = 30.416667;
    
    public readonly array $hours;
    

    private function do40(array $a) : array {
	if (!$a) return [];

	$now = time();

	$s = $now - strtotime($a[1]);
	$hus = number_format($s); unset($hus);
	$elapM = $s / (self::dperm * DAY_S); unset($s);
	$paid  = $elapM * $a[3]; unset($elapM);
	$dollarsPerHour   = $paid /  $a[2];
	$worked = $a[4] * $a[2];
	$dolahead  = $worked - $paid; unset($worked, $paid);
	$hoursAhead = $dolahead / $a[4];
	$targdpd = $a[3] / self::dperm;
	$targhpd = $targdpd / $a[4]; unset($targhpd);
	$daysAhead = $dolahead / $targdpd; unset($dolahead, $targdpd);
	$Uearnto = roint($now + $daysAhead * DAY_S); unset($now);
	$earnedTo = date('r', $Uearnto); unset($Uearnto);
	unset($a);
	
	return get_defined_vars();
    }


    private function findMarker(array $a) : array {
	foreach($a as $i => $c) {
	    if (!$c) continue;
	    if (!trim($c)) continue;
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

	$ret = [];
	$fs = glob(self::source . '*.ods');
	foreach($fs as $f) {
	    $this->do20 ($f);
	    $t = $this->parse30($f);
	    if (!$t) continue;
	    $ret[$t['projectName']] = $t;
	}

	$this->hours = $ret;

	return;
    }



    private function parse30(string $f) : array {
	$csv = $this->otoc($f);
	$t = file_get_contents($csv);
	$a = str_getcsv($t);
	$dat = $this->do40($this->findMarker($a));
	if (!$dat) return [];
	$dat['projectName'] = pathinfo($f, PATHINFO_FILENAME);
	$dat['Ufile'  ] = filemtime($f);
	return $dat;
    }

    private function otoc(string $f) {
	return str_replace('.ods', '.csv', $f);
    }

    private function already(string $f) : bool {
	$csv = $this->otoc($f);
	if (!file_exists($csv)) return false;
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



