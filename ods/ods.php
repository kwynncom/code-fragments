<?php

require_once('/opt/kwynn/kwutils.php');

class odsFirstSheetCl {
    const source = '/var/kwynn/hours/';
    const marker = 'autoKw18';
    const fs = ['marker', 'start', 'hours', 'permonth', 'rate']; // just a reminder
    const dperm = 30.416667;
    
    public  readonly array $hours;
    private	     array $asof;
    

    private function do40(array $a) : array {
	if (!$a) return [];

	$now = time();

	$s = $now - strtotime($a[1]);
	$hus = number_format($s); unset($hus);
	$elapM = $s / (self::dperm * DAY_S); unset($s);
	$paid  = $elapM * $a[3]; unset($elapM);
	$dphNow   = $paid /  $a[2];
	$rate = $a[4];
	$worked = $a[4] * $a[2];
	$dolahead  = $worked - $paid; unset($worked, $paid);
	$hoursAhead = $dolahead / $a[4];
	$targdpd = $a[3] / self::dperm;
	$targhpd = $targdpd / $a[4]; unset($targhpd);
	$daysAhead = $dolahead / $targdpd; unset($dolahead, $targdpd);
	$UEarnedTo = roint($now + $daysAhead * DAY_S); unset($now);
	// $earnedToHu = date('r', $UearnedTo); 
	unset($a);
	
	return get_defined_vars();
    }


    private function findMarker(array $a) : array {
	foreach($a as $i => $c) {
	    if (!$c) continue;
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
	$fs = glob(self::source . '*.csv');
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
	$t = file_get_contents($f);
	$a = str_getcsv($t);
	$dat = $this->do40($this->findMarker($a));
	if (!$dat) return [];
	$dat['projectName'] = pathinfo($f, PATHINFO_FILENAME);
	$dat['Ufile'  ] = $this->asof[$this->ctoo($f)];
	return $dat;
    }

    private function getmt(string $csv) {
	
    }

    private function ctoo(string $f) {
	return str_replace('.csv', '.ods', $f);
    }

    private function otoc(string $f) {
	return str_replace('.ods', '.csv', $f);
    }


    private function setAsOf(string $f, int $U) : int {
	$this->asof[$f] = $U;
	return $U;
    }

    private function already(string $csv) : bool {
	if (!file_exists($csv)) return false;

	$pdf = $this->ctoo($csv);
	if (file_exists($pdf)) {
	    $cm = filemtime($csv);
	    $pm = filemtime($pdf);
	    $this->setAsOf($pdf, $pm);
	    if ($cm >= $pm) return true;
	    else return false;
	}  

	return true;
    }

    private function do20(string $csv)  {

	if ($this->already($csv)) return;
	$pdf = $this->ctoo($csv);
	if (!is_readable($pdf)) return;

	$this->setAsOf($pdf, filemtime($pdf));
	$c = 'soffice --headless --convert-to csv ' . $pdf . ' --outdir ' . self::source;
	$res = shell_exec($c);
	$csv = $this->otoc($pdf);
	kwas(is_readable($csv) && filemtime($csv) >= $this->asof[$pdf], 'ods to csv fail (err # 0614126)');
	return;
    }
}

if (didCLICallMe(__FILE__)) new odsFirstSheetCl();



