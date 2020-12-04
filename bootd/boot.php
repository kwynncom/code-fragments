<?php

require_once('/opt/kwynn/mongodb2.php'); // see note at bottom

class boot_tracker extends dao_generic_2 {
    
    const dbName = 'boot';
    
    private function __construct() {
	parent::__construct(self::dbName, __FILE__);
	$this->creTabs(['b' => 'boot']);
	$this->t10();
	
	$this->p10();
    }
    
    private function p10() {
	$n = nanopk();
	$r = $this->bcoll->getseq2(true);
	return;
    }
    
    public static function getID() {
	$o = new self();
	$o->doit();
    }
    
    private function t10() { if (!function_exists('nanopk')) die('nanopk() needed from https://github.com/kwynncom/readable-primary-key/tree/master/php_extension'); }

}

// mongodb2.php is at https://github.com/kwynncom/kwynn-php-general-utils