<?php

class adbDisplayCl {
    public function __construct(array $dat) {
	$this->do10($dat);
    } 

    private function do10(array $ain) {
	$s  = '';
	foreach($ain as $sn => $a) {
	    $U  = $a['Uat'];
	    $s .= $a['battery']->level;
	    $s .= '%';
	    $s .= ' ';
	    $s .= $a['battery']->chargingBy ? '++' : '--';
	    $s .= ' ';
	    $s .= date('H:i', $U);
	    $s .= ' ';
	    $s .= $a['gen']['ro.product.manufacturer'];
	    $s .= ' ';
	    $s .= $U;

	}

	if (iscli()) echo($s . "\n");
    }

}

