<?php

require_once('/opt/kwynn/mongodb2.php'); // see note at bottom

class boot_tracker extends dao_generic_2 {
    
    const dbName = 'boot';
    const bootTimeMargin = 3;
    const shmsysvpid = 'm';
    const shmSize = 500;
    const shmDatKey = 1;
    const testUntil = '2020-10-15 02:00';
    
    private function __construct() {
	parent::__construct(self::dbName, __FILE__);
	$this->locko = new sem_lock(__FILE__, 'l');
	$this->creTabs(['b' => 'boot']);
	$this->meta10();
	$this->t10();
	$this->p10();
    }
    
    private static function getShmSysv() { 
	return ftok(__FILE__, self::shmsysvpid); }    
    
    private function meta10() { $this->bcoll->createIndex(['nano.Uboot' => -1], ['unique' => true]);    }
    
    private function p10() {
	$newn = nanopk();
	$newb = $newn['Uboot'];
	$r = $this->bcoll->findOne(['Uboot' => ['$gte' => $newb - self::bootTimeMargin, '$lte' => $newb + self::bootTimeMargin]]);
	if ($r) {
	    $this->p30($r);
	    return;
	}
	$this->p20($newb);
	
	return;
    }
    
    private function p20($Uboot) {
	$dat = $this->bcoll->getseq2(true, $Uboot, false);
	$dat['Uboot'] = $Uboot;
	$dat['rboot'] = date('r', $Uboot);
	$this->bcoll->insertOne($dat);
	$this->p30($dat);
	return;
    }
    
    private function p30($din) {
	$shma = self::getShmSeg('w');
	shm_put_var($shma, self::shmDatKey, $din);
    }
    
    private static function getShmSeg($rw = 'r') {
	if ($rw === 'w') $perm = 0644;
	else		 $perm = 0444;
	
	kwas($r = shm_attach(self::getShmSysv(), self::shmSize, $perm), 'attached failed' . "\n");
	return $r;
	
    }
    
    private static function getI() {
	$shma = self::getShmSeg();
	if (      shm_has_var($shma, self::shmDatKey))
           return shm_get_var($shma, self::shmDatKey);
	return false;
	
    }
    
    private static function rmShm($rmblock = false) {
	$shma = self::getShmSeg('w');
	if (shm_has_var   ($shma, self::shmDatKey))
	    shm_remove_var($shma, self::shmDatKey);	
	
	if ($rmblock) {
	    kwas(shm_remove($shma), 'failed to rm whole block / segment');
	    echo('block removed' . "\n");
	    kwas(shm_detach($shma), 'detach failed');
	    echo('detached' . "\n");
	    echo('Exiting...' . "\n");
	    exit(0);
	}
    }
    
    public static function get() {
	self::clean();
	self::t30();
	$r = self::getI();
	if ($r) return $r;
	$o = new self();
	$r = $o->getI();
	return $r;
    }
    
    private static function clean() {
	global $argv;
	
	if (in_array('-clean', $argv)) self::rmShm(1);
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
	self::rmShm();
    }
    
    private static function t30()  { 
	if (!self::isTest()) return;
	self::rmShm(); 
	
    }

}

// mongodb2.php is at https://github.com/kwynncom/kwynn-php-general-utils