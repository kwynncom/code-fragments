<?php

require_once('/opt/kwynn/kwutils.php');

class xactsGetCl {
    
    const pyCmd = 'python3 ' . __DIR__ . '/../pygnucash/main.py';
    const cachePre  = '/var/kwynn/gnucash/cache';
    const cacheSfx = '.json';
    const xmlf = '/var/kwynn/gnucash/gnucash.xml.gnucash';

    public readonly float $balStart;
    public readonly array $currXacts;
    private	    bool  $iAmCache = false;

    public function __construct() {
	$this->do10();
	$this->putCache();
    }

    private function getCacheName() : string {
	$f  = '';
	$f .= self::cachePre;
	$f .= '-';
	$f .= PHP_SAPI === 'cli' ? 'cli' : 'www';
	$f .= self::cacheSfx;
	return $f;
    }

    private function getCacheTxt() {
	$this->iAmCache = true;
 	return file_get_contents($this->getCacheName());
    }	

    private function getCurrJSON() : string {
	$doRaw = false;
	$cf = $this->getCacheName();
	if (!file_exists($cf)) $doRaw = true;
	
	kwas(file_exists(self::xmlf), 'GNUCash XML does not exist err #201034');
	$rp = realpath(self::xmlf);
	

	if (!$doRaw && (filemtime($cf) < filemtime($rp))) $doRaw = true;

	if ($doRaw) return $this->getRaw();
	else	    return $this->getCacheTxt();
    }

    private function putCache() {

	if ($this->iAmCache) return;

	$f = $this->getCacheName();

	file_put_contents($f, '');
	kwas(chmod($f, 0600), "cannot chmod $f");
	$j = json_encode($this->currXacts, JSON_PRETTY_PRINT);
	$n = file_put_contents($f, $j); 
	kwas($n && $n === strlen($j), "bad write to $f");
	unset($f, $j, $n);
	
    }

    private function do10() {
	$t = $this->getCurrJSON();
	$this->do20A($t);
	
    }

    private function do20A(string $t) {

	$afwd = json_decode($t, true); unset($t);
	if (true && !$this->iAmCache) $a = array_reverse($afwd); 
	else 
	$a = $afwd;

	unset($afwd);

	kwas($a && is_array($a), 'JSON did not yield initial array err# 063933');

	$startBal = 0;

	foreach($a as $i => $r) {
	    if ($r['reconciled'] === 'y') {
		$startBal = $r['bal'];
		break;
	    }
	} unset($r);

	if (!is_float($startBal)) $startBal = floatval($startBal);

	if (!$this->iAmCache) {
	    $a = array_slice($a, 0, $i + 1); unset($i);
	    $a = array_reverse($a);
	}
	
	$this->balStart = $startBal; unset($startBal);
	$this->currXacts = $a;

	return;

    }

    private function getRaw() : string {
	 $t = shell_exec(self::pyCmd);
	 kwas($t && is_string($t) && strlen($t) > 10, 'GNU account file too small - err # 062919');
	 return $t;
    }
}
