<?php

require_once('fileGet.php');

class balancesCl {

    private readonly float $balStart;
    private readonly array $currx;

    public function __construct() {
	$this->init10();
    }

    private function init10() {
	$o = new xactsGetCl();
	$this->balStart = $o->balStart;
	$this->currx    = $o->currXacts;

	$disp = $this->currx;
	var_dump($disp);

	
    }

}

if (didCLICallMe(__FILE__)) new balancesCl();

