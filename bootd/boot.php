<?php

require_once('/opt/kwynn/mongodb2.php'); // see note at bottom

class boot_tracker extends dao_generic_2 {
    
    const dbName = 'boot';
    const bootTimeMargin = 3;
    const shmsysvpid = 'm';
    
    private function __construct() {
	parent::__construct(self::dbName, __FILE__);
	$this->locko = new sem_lock(__FILE__, 'l');
	$this->creTabs(['b' => 'boot']);
	$this->meta10();
	$this->t10();
	$this->p10();
    }
    
    private static function getShmSysv() { return ftok(__FILE__, self::shmsysvpid); }    
    
    private function meta10() { $this->bcoll->createIndex(['nano.Uboot' => -1], ['unique' => true]);    }
    
    private function p10() {
	$newn = nanopk();
	$newb = $newn['Uboot'];
	// $newb = 0;
	
	$r = $this->bcoll->findOne(['nano.Uboot' => ['$gte' => $newb - self::bootTimeMargin, '$lte' => $newb + self::bootTimeMargin]]);
	if ($r) return;
	$this->p20();
	
	return;
    }
    
    private function p20() {
	$n = nanopk();
	$dat = $this->bcoll->getseq2(true, $n['Uboot']);
	$dat['nano'] = $n;
	$this->bcoll->insertOne($dat);
	$this->p30($dat);
	return;
    }
    
    private function p30($din) {
	$shma = shm_attach(self::getShmSysv(), 500, 0644);
	shm_put_var($shma, 1, $din);

	
    }
    
    public static function getID() {
	$o = new self();
    }
    
    private function t10() { if (!function_exists('nanopk')) die('nanopk() needed from https://github.com/kwynncom/readable-primary-key/tree/master/php_extension'); }

}

// mongodb2.php is at https://github.com/kwynncom/kwynn-php-general-utils