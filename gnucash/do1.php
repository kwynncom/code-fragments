<?php

require_once('fileGet.php');

class balancesCl {

    private readonly array $currx;

    public function __construct() {
	$this->init10();
    }

    private function init10() {
	$o = new xactsGetCl();
	$this->currx    = $o->currXacts;
	var_dump($this->currx);
    }

}

if (didCLICallMe(__FILE__)) new balancesCl();

