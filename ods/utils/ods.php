<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');

class odsFirstSheetCl {
    const source = '/var/kwynn/hours/';
    const marker = 'autoKw18';
    const fs = ['marker', 'start', 'hours', 'permonth', 'rate'];
    const dperm = 30.416667;
    
    public  readonly array $hours;
    public  readonly array $indat;
    private	     array $asof;

    private static function procA(array $ain, array $vars) : array {
	$ret = [];

	kwas($ain[0] === self::marker, 'array bad err # 080827-20');
	return [];

    }

    public static function calcs(array $a) : array {
	if (!$a) return [];

	kwas($a[0] === self::marker, 'array bad err # 080827-30');

	$now = time();

	$s = $now - strtotime($a[1]);
	$hus = number_format($s); unset($hus);
	$elapM = $s / (self::dperm * DAY_S); unset($s);
	$paid  = $elapM * $a[3]; unset($elapM);
	$dph   = $paid /  $a[2];
	$rate = self::getRate($a[4]);
	$worked = $a[4] * $a[2];
	$dolahead  = $worked - $paid; unset($worked, $paid);
	$hours = $dolahead / $a[4];
	$targdpd = $a[3] / self::dperm;
	$targhpd = $targdpd / $a[4]; unset($targhpd);
	$days = $dolahead / $targdpd; unset($dolahead, $targdpd);
	$earnedTo = roint($now + $days * DAY_S); unset($now);
	
	$vars = get_defined_vars(); 
	$input = self::procA($a, $vars); 	unset($a);

	return $vars;
    }

    private static function getRate($vin) : int | float {
	$fl = floatval($vin);
	$iv = roint   ($vin);
	if (abs($fl - $iv) < 0.001) return $iv;
	return $fl;
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
	$this->toDB();
    }

    private function toDB() {
	require_once(__DIR__ . '/db.php');
	odsDBCl::put($this->hours);
    }

    private function do10() {

	$ret = [];
	$db  = [];

	$fs = glob(self::source . '*.csv');
	foreach($fs as $f) {
	    $this->do20 ($f);
	    $t = $this->parse30($f);
	    if (!$t) continue;
	    $proj = $t['calcs']['project'];
	    $ret[$proj] = $t['calcs']; unset($t['calcs']);
	    $db [$proj] = $t;
	}

	$this->hours = $ret;

	return;
    }



    private function parse30(string $f) : array {
	$t = file_get_contents($f);
	$aall = str_getcsv($t);
	$a = $this->findMarker($aall);
	$dat = $this->calcs($a);
	if (!$dat) return [];
	$uq = [];
	$uq['project'] = pathinfo($f, PATHINFO_FILENAME);
	$uq['Ufile'  ] = $this->asof[$this->ctoo($f)];
	$ret = kwam($dat, $uq);
	return ['uq' => $uq, 'calcs' => $ret, 'input' => $a];
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



