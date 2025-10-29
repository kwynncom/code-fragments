<?php

declare(strict_types=1);

require_once('config.php');

class getHoursSACl implements hoursIntf {
    

    const minFSize = 12;

    private readonly object $lo;
    public  readonly array  $odat;
    private readonly string $ods;
    public  readonly int    $mtime;
    private readonly string $csv;
    public  readonly string $project;

    private readonly bool   $wouldBlock;

    private function lock($async = false) : bool {
	$id = $this->project . '_odsToCSVKw25';
	if (!isset($this->lo)) $this->lo = new sem_lock($id);
	return $this->lo->lock($async);
    }

    private function setFileInfo(string $sfx) {
	$p = self::filePath . $this->project . '.' . $sfx;
	if (!($sfx === 'csv' && !file_exists($p))) {
	    kwas(is_readable($p), $this->project .   " $sfx file not readable");
	    kwas(filesize   ($p) >= self::minFSize,  " $sfx not big enough");
	}
	if ($sfx === 'ods') $this->mtime = filemtime($p);
	$this->{$sfx} = $p;

    }

    private function do10() {
	$this->setFileInfo('csv');
	if (
		!(
		        (file_exists($this->csv) && (filemtime($this->csv) >= $this->mtime))
		    ||	 $this->wouldBlock
		)
	) { $this->doActual(); }

	$this->popDat();

    }

    private function popDat()  {
	$dat = $this->parseCSV();

	$ret = [];
	$ret['status'] = 'OK';
	$ret['error' ] = '';
	$ret['all'] = $dat;
	$ret['Ufile'] = $this->mtime;
	$ret['project'] = $this->project;
	$this->odat = $ret;
	
    }

    private function parseCSV() : array {
	$a =  str_getcsv(file_get_contents($this->csv));
	return !$a || !is_array($a) ? [] : $a;
    }	

    private function doActual() {
	$c = 'soffice --headless --convert-to csv ' . $this->ods . ' --outdir ' . self::filePath;
	$res = shell_exec($c);
	$this->checkConvOrDie();
	return;

    }

    private function checkConvOrDie() {

	$ir  = is_readable($this->csv);
	$sz = filesize($this->csv) ;
	clearstatcache(true, $this->csv);
	$ct =  filemtime($this->csv);
	$ot = $this->mtime;

	kwas($ir && $sz >= self::minFSize && $ct >= $this->mtime, 
		'ods to csv fail (err # 0614126)');
    }

    private function parseProj(string $s) : string {
	try { 
	    kwas(trim($s), 'bad project name');
	    if (!strpos($s, '.')) return $s;
	    $t = trim(pathinfo($f, PATHINFO_FILENAME));
	    kwas($t, 'cannot parse proj');
	    return $t;
	} catch(Throwable $ex) {
	    $this->project = 'error';
	    throw $ex;
	}
    }

    public static function getMTime(string $proj) : int {
	$o = new self($proj, 'mtime');
	return $o->getMTimeI();
    }

    public function getMTimeI() : int {
	if (	       !isset($this->ods)) return 0;
	if (     !is_readable($this->ods)) return 0;
	else				   return filemtime($this->ods);
    }

    public static function getProjects() : array {
	$fs = self::getAllFiles();
	if (!$ps) return [];

	$ret = [];
	foreach($fs as $p) {
	    $o = new self($p);
	    $ret[$o->project] = $o->mtime;
	    
	}   

	return $ret;
    }

    public static function getAllFiles() : array {
	$fs = glob(self::glob);
	if (!$fs) return [];
	return $fs;	
    }

    private function __construct(string $proj, string $want = '') {
	try { 
	    $this->project = $this->parseProj($proj); unset($proj);
	    $this->wouldBlock = $this->lock(true);
	    $this->lock();
	    $this->setFileInfo('ods');
	    if ($want !== 'mtime') $this->do10();
	    $this->lo->unlock();
	} catch(Throwable $ex) { $this->handleEx($ex); }
    }

    private function handleEx($ex) { 
	$a = [];
	$a['status'] = 'error';
	$a['error' ] = $ex->getMessage();
	$a['project'] = $this->project;
	$a['Ufile'] = 0;
	if (!isset($this->mtime)) $this->mtime = 0;
	$this->odat = $a;
    }

    public static function get(string $proj) : array {
	$o = new self($proj);
	return $o->odat;
    }

    

}
