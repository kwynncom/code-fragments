<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/validate.php');

class odsFirstSheetCl {
    const source = '/var/kwynn/hours/';

    const dperm = 30.416667;
    
    public  readonly array $hours;
    public  readonly array $input;
    private	     array $asof;

    public static function getCalcs(array $ain) : array {
	if (!$ain) return [];

	$input = $a = odsArrValCl::getValid($ain); unset($ain);

	$now = time();

	$Ustart = $a['Ustart'];
	$hoursWorked = $a['hours'];
	$s = $now - $Ustart;
	$hus = number_format($s); unset($hus);
	$elapM = $s / (self::dperm * DAY_S); unset($s);
	$permonth = $a['permonth'];
	$paid  = $elapM * $permonth; unset($elapM);
	$dph   = $paid /  $hoursWorked;
	$rate = $a['rate']; unset($a);
	$worked = $rate * $hoursWorked;
	$dolahead  = $worked - $paid; unset($worked, $paid);
	$hours = $dolahead / $rate;
	$targdpd = $permonth / self::dperm;
	$targhpd = $targdpd / $rate; unset($targhpd);
	$days = $dolahead / $targdpd; unset($dolahead, $targdpd);
	$earnedTo = roint($now + $days * DAY_S); unset($now);
	
	$vars = get_defined_vars(); 

	return $vars;
    }



    private function findMarker(array $a) : array {
	foreach($a as $i => $c) {
	    if (!$c) continue;
	    if (trim($c) !== odsArrValCl::marker) continue;
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
	odsDBCl::put($this->input);
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
	    $db [$proj] = kwam($t['uq'], $t['input']);
	}

	$this->hours = $ret;
	$this->input = $db;

	return;
    }



    private function parse30(string $f) : array {
	$t = file_get_contents($f);
	$aall = str_getcsv($t);
	$a = $this->findMarker($aall);
	$dat = $this->getCalcs($a);
	if (!$dat) return [];
	$uq = [];
	$uq['project'] = pathinfo($f, PATHINFO_FILENAME);
	$uq['Ufile'  ] = $this->asof[$this->ctoo($f)];
	$input = $dat['input']; 
	unset   ($dat['input']);
	$ret = kwam($dat, $uq);
	return ['uq' => $uq, 'calcs' => $ret, 'input' => $input];
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
	$this->doCmd($c, $pdf);
	$csv = $this->otoc($pdf);
	kwas(is_readable($csv) && filemtime($csv) >= $this->asof[$pdf], 'ods to csv fail (err # 0614126)');
	return;
    }

    private function doCmd(string $c, string $f)  {
	$lo = new sem_lock($f);
	if ($lo->lock(true)) {
	    if (iscli()) echo('Another process has a cmd lock.  Skipping.' . "\n");
	    return;
	}
	$res = shell_exec($c);
	$lo->unlock();
	return $res;
    }
}

if (didCLICallMe(__FILE__)) new odsFirstSheetCl();



