<?php

require_once('/opt/kwynn/mongodb2.php'); // see note at bottom
require_once(__DIR__ . '/../mid/db/get.php');

class boot_tracker extends dao_generic_2 {
    
    const dbName = 'boot';
    const bootTimeMargin = 3;
    const shmsysvpid = 'm';
    const shmSize = 200;
    const shmDatKey = 1;
    const testUntil = '2020-10-15 02:00';

    
    private function __construct($type = false) {
	if ($type !== 'get' || $type === 'rodb') {
	    parent::__construct(self::dbName, __FILE__);
	    if ($type === 'rodb') {
		$this->rodb = true;
		$this->creTabs(['b' => 'boot']);
		return;
	    }
	    $this->locko = new sem_lock(__FILE__, 'l');
	    $this->creTabs(['b' => 'boot']);
	    $this->meta10();
	    $this->t10();
	    $o->clean();
	    $o->t30();
	}
	$this->shma = false;
    }
    
    public function __destruct() {
	if (!isset($this->shma) || !$this->shma) return;
	kwas(shm_detach($this->shma), 'detach failed - __destruct()' . "\n");
	// echo("detached\n");
    }
    
    private static function getShmSysv() { 
	return ftok(__FILE__, self::shmsysvpid); }    
    
    private function meta10() { $this->bcoll->createIndex(['Ubest' => -1], ['unique' => true]);    }
    
    private function p10() {
	$newn = uptime();
	$newb = $newn['Ubest'];
	$r = $this->bcoll->findOne(['Ubest' => ['$gte' => $newb - self::bootTimeMargin, '$lte' => $newb + self::bootTimeMargin]]);
	if ($r && isset($this->rodb) && $this->rodb) return $r;
	if ($r) {
	    $this->p30($r);
	    return;
	}
	$this->p20($newb);
	
	return;
    }
    
    public static function getDB() {
	$o = new self('rodb');
	return $o->p10();
    }
    
    private function p20($Ubest) {
	$dat = $this->bcoll->getseq2(true, $Ubest, false);
	$dat['Ubest'] = $Ubest;
	$dat['rbest'] = date('r', $Ubest);
	$dat['mid'] = machine_id_get::get();
	$this->bcoll->insertOne($dat);
	$this->p30($dat);
	return;
    }
    
    private function p30($din) {
	$this->setShmSeg();
	kwas(shm_put_var($this->shma, self::shmDatKey, $din), "putVar failed p30()\n");
    }
    
    private function setShmSeg() {
	if ($this->shma) return $this->shma;
	kwas($r = shm_attach(self::getShmSysv(), self::shmSize, 0666), 'attached failed' . "\n");
	$this->shma = $r;
    }
    
    private function getI() {
	$var = false;
	$this->setShmSeg();
	if (      shm_has_var($this->shma, self::shmDatKey))
           $var = shm_get_var($this->shma, self::shmDatKey);
	return $var;
	
    }
    
    private function rmShm($rmblock = false) {
	$this->setShmSeg();
	if (shm_has_var   ($this->shma, self::shmDatKey)) {
	    echo("removing var\n");
	    kwas(shm_remove_var($this->shma, self::shmDatKey),'rm var failed rmShm()');
	}
	
	if ($rmblock) {
	    kwas(shm_remove($this->shma), 'failed to rm whole block / segment');
	    echo('block removed' . "\n");
	    echo('Exiting...' . "\n");
	    exit(0);
	}
    }
    
    public static function get() {
	$o = new self('get');
	$r = $o->getI();
	if ($r) return $r;
	$o = new self();
	$o->p10();
	$r = $o->getI();
	return $r;
    }
    
    private function clean() {
	global $argv;
	
	if (in_array('-clean', $argv)) $this->rmShm(1);
    }
    
    private function t10() { 
	if (!function_exists('nanopk')) die('nanopk() needed from https://github.com/kwynncom/readable-primary-key/tree/master/php_extension'); 
	$this->t20();
    }
    
    private static function isTest() { 
	return time() < strtotime(self::testUntil);
    }
    
    private function t20() {
	if (!self::isTest()) return;
	$this->bcoll->rmSeq2();
	$this->bcoll->drop();
	$this->rmShm();
    }
    
    private static function t30()  { 
	if (!self::isTest()) return;
	$this->rmShm(); 
	
    }

}

// mongodb2.php is at https://github.com/kwynncom/kwynn-php-general-utils