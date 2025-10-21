<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/validate.php');

class odsDoCl {
    const source = '/var/kwynn/hours/';

    private	     array  $asoff;
    private readonly object $lo;
    public  readonly array  $cvsArrs;
    private readonly array  $asofdb;

    public static function get($gt = []) : array {
	$o = new self($gt);
	if (!isset($o->cvsArrs)) return [];
	return     $o->cvsArrs;
    }

    private static function getProjectName(string $f) : string { 
	return pathinfo($f, PATHINFO_FILENAME);    
    }

    private function pop() {
	$fs = glob(self::source . '*.csv');
	$ret = [];
	foreach($fs as $f) {
	    $key = self::getProjectName($f);
	    $v = $this->pop20($f);
	    if (!$v) continue;
	    $ret[$key] = [];
	    $ret[$key]['all'] = $v;
	    $ret[$key]['Ufile'] = $this->getAsOf($this->ctoo($f));
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

    private function __construct(array $ifgtin) {
	$this->asofdb = $ifgtin; unset($ifgtin);
	$wb = $this->lock(true);
	$this->lock();
	if (!$wb) $this->doFileLoop(); unset($wb);
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
    private function setAsOf(string $f, int $U) : int { $this->asoff[$f] = $U; return $U;    }
    private function getAsOf(string $f) {
	if (!isset ($this->asoff[$f])) {
	    if (file_exists($f)) $this->setAsOf ($f, filemtime($f));
	    else $this->setAsOf($f, 0);
	}
	return  $this->asoff    [$f];
    }

    private function already(string $csv) : bool {

	$mts = [];
	$mts['ods'] = $this->getAsOf($this->ctoo($csv));
	$mts['db' ] = $this->getAsOfDB($csv);

	return $mts['db'] >= $mts['ods'];
    }
    
    private function getAsOfDB(string $f) {
	$key = $this->getProjectName($f);
	if (isset( $this->asofdb[$key]['Ufile'])) {
	    return $this->asofdb[$key]['Ufile'];
	}
	return 0;

    }

    private function doFile(string $csv)  {
	if ($this->already($csv)) return;
	$ods = $this->ctoo($csv);
	if (!is_readable($ods)) return;
	

	$c = 'soffice --headless --convert-to csv ' . $ods . ' --outdir ' . self::source;
	$res = shell_exec($c);
	$csv = $this->otoc($ods);
	$this->setAsOf($csv, filemtime($csv));
	kwas(is_readable($csv) && $this->getAsOf($csv) >= $this->getAsOf($ods), 
		'ods to csv fail (err # 0614126)');

	return;
    }
}

if (didCLICallMe(__FILE__)) odsDoCl::get();



