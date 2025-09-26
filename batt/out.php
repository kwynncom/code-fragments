<?php

class adbDisplayCl {

    private readonly array $a;

    public function __construct(array $din) {
	$this->a = $din; unset($din);
	$this->branch10();
    } 

    private function isNumOnly() : bool {
	global $argv;
	if (!isset($argv[1])) return false;
	$s = strtolower($argv[1]);
	if ($s === 'no') return true;
	if (substr($s, 0, 3) === 'num') return true;
	return false;
    }

    private function branch10() {
	if (!$this->isNumOnly()) return $this->do10();
	return $this->doNum();
	
    }

    private function doNum() {
	kwas(count($this->a) === 1, '0 or 2+ phones'); 
	$a = array_values($this->a)[0];
	$i = intval($a['battery']->level);
	kwas($i >= 0 && $i <= 100, 'bad number-only batt level');
	if ($i === 100) $i = 9;
	echo($i . "\n");
	return;
    }

    private function do10(array $ain) {
	$s  = '';
	$f  = '';

	foreach($ain as $sn => $a) {
	    $batt = $a['battery'];
	    $U  = $a['Uat'];
	    $s .= $batt->level;
	    $f  = $s . ' ';
	    $s .= '%';
	    $s .= ' ';
	    $ch = $batt->chargingBy ? '++' : '--';
	    $s .= $ch;
	    $f .= $ch;
	    $s .= ' ';
	    $f .= ' ';
	    $bd = sprintf('%0.03f', $batt->V);
	    $s .= $bd;
	    $f .= $bd;
	    $s .= 'V';
	    $s .= ' '; 
	    $f .= ' ';
	    $s .= number_format($batt->uAh) . 'uAh';
	    $f .= sprintf('%7s', $batt->uAh);
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
	    $f .= 'v925-8';
	    $f .= ' ';
	    $f .= $U;
	    $s .= ' ';
	    $f .= ' ';

	}

	$f .= "\n";

	$fn = '/var/kwynn/batt.txt';
	$n = file_put_contents($fn, $f, FILE_APPEND);
	kwas($n === strlen($f), 'bad write to ' . $fn);

	if (iscli()) {
	    echo(shell_exec('tail -n 20 ' . $fn));
	    echo($s . "\n");
	    if (false) echo($f);
	}



    }

}

