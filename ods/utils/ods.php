<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/validate.php');

class odsDoCl {
    const source = '/var/kwynn/hours/';

    private	     array  $asof;
    private readonly object $lo;
    public  readonly array  $cvsArrs;

    public static function get() : array {
	$o = new self();
	if (!isset($o->cvsArrs)) return [];
	return     $o->cvsArrs;
    }

    private static function getProj(string $f) : string { return pathinfo($f, PATHINFO_FILENAME);    }

    private function pop() {
	$fs = glob(self::source . '*.csv');
	$ret = [];
	foreach($fs as $f) {
	    $key = self::getProj($f);
	    $v = $this->pop20($f);
	    if (!$v) continue;
	    $ret[$key] = [];
	    $ret[$key]['all'] = $v;
	    $ret[$key]['Ufile'] = $this->getAsOf($f);
	    $ret[$key]['project'] = $key;
	}

	$this->cvsArrs = $ret;
	return;
    }
   
    private function pop20(string $f) : array {
	if (!is_readable($f)) return [];
	$t = file_get_contents($f); unset($f);
	if (!$t || !is_string($t)) return [];
	if (strlen($t) < 4) return [];
	$a =  str_getcsv($t); unset($t);
	if (!$a || !is_array($a)) return [];
	return $a;
    }

    private function __construct() {
	$wb = $this->lock(true);
	$this->lock();
	if (!$wb) $this->doFileLoop();
	$this->pop();
	$this->lo->unlock();
    }

    private function lock($async = false) {
	if (!isset($this->lo)) $this->lo = new sem_lock(realpath(__FILE__));
	return $this->lo->lock($async);
    }


    private function doFileLoop() { 
	foreach(glob(self::source . '*.ods') as $f) $this->doFile($this->otoc($f));  
    }

    private function otoc(string $f) { return str_replace('.ods', '.csv', $f); }
    private function ctoo(string $f) { return str_replace('.csv', '.ods', $f); }
    private function setAsOf(string $f, int $U) : int { $this->asof[$f] = $U; return $U;    }
    private function getAsOf(string $f) {
	if (!isset ($this->asof[$f])) 
		$this->setAsOf ($f, filemtime($f));
	return  $this->asof    [$f];
    }

    private function already(string $csv) : bool {
	if (!file_exists($csv)) return false;

	$pdf = $this->ctoo($csv);
	if (file_exists($pdf)) {
	    $cm = $this->getAsOf($csv);
	    $pm = $this->getAsOf($pdf);
	    if ($cm >= $pm) return true;
	    else return false;
	}  

	return true;
    }

    private function doFile(string $csv)  {
	if ($this->already($csv)) return;
	$pdf = $this->ctoo($csv);
	if (!is_readable($pdf)) return;

	$c = 'soffice --headless --convert-to csv ' . $pdf . ' --outdir ' . self::source;
	$res = shell_exec($c);
	$csv = $this->otoc($pdf);
	$this->setAsOf($csv, filemtime($csv));
	kwas(is_readable($csv) && $this->getAsOf($csv) >= $this->getAsOf($pdf), 
		'ods to csv fail (err # 0614126)');

	return;
    }
}

if (didCLICallMe(__FILE__)) odsDoCl::get();



