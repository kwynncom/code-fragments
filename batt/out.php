<?php

class adbDisplayCl {
    public function __construct(array $dat) {
	$this->do10($dat);
    } 

    private function do10(array $ain) {
	$s  = '';
	$f  = '';

	foreach($ain as $sn => $a) {
	    $batt = $a['battery'];
	    $U  = $a['Uat'];
	    $s .= $batt->level;
	    $s .= '%';
	    $s .= ' ';
	    $s .= $batt->chargingBy ? '++' : '--';
	    $s .= ' ';
	    $f  = $s;
	    $s .= sprintf('%0.3f', $batt->V);
	    $f .= $batt->V;
	    $s .= 'V';
	    $s .= ' '; 
	    $f .= ' ';
	    $s .= number_format($batt->uAh) . 'uAh';
	    $f .= $batt->uAh;
	    $s .= ' ';
	    $f .= ' ';
	    $hu = date('H:i:s D', $U);
	    $s .= $hu;
	    $f .= $hu;
	    $s .= ' ';
	    $f .= ' ';
	    $s .= $a['gen']['ro.product.manufacturer'];
	    $f .= $a['gen']['ro.serialno'];
	    $f .= ' ';
	    $s .= ' ';
	    $f .= 'v925-1';
	    $f .= ' ';
	    $f .= $U;
	    $s .= ' ';
	    $f .= ' ';

	}

	$f .= "\n";
	if (iscli()) {
	    echo($s . "\n");
	    if (false) echo($f);
	}

	$fn = '/var/kwynn/batt.txt';
	$n = file_put_contents($fn, $f, FILE_APPEND);
	kwas($n === strlen($f), 'bad write to ' . $fn);

    }

}

