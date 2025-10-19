<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/validate.php');
require_once('ods.php');

class odsFirstSheetCl {
    const source = '/var/kwynn/hours/';

    const dperm = 30.416667;
    
    public  readonly array $hours;
    public  readonly array $input;
    private	     array $asof;

    public static function getCalcs(array $ain) : array {
	$o = new self($ain);

    }
    

    private static function getCalcsI(array $ain) : array {
	if (!$ain) return [];

	$input = $a = odsArrValCl::getValid($ain); unset($ain);

	$now = time();

	$Ustart = $a['Ustart'];
	$hoursWorked = $a['hours'];
	$s = $now - $Ustart;
	$hus = number_format($s); unset($hus);
	$elapM = $s / (self::dperm * DAY_S); unset($s);
	$permonth = $a['permonth'];
	$paid  = $elapM * $permonth; unset($elapM);
	$dph   = $paid /  $hoursWorked;
	$rate = $a['rate']; unset($a);
	$worked = $rate * $hoursWorked;
	$dolahead  = $worked - $paid; unset($worked, $paid);
	$hours = $dolahead / $rate;
	$targdpd = $permonth / self::dperm;
	$targhpd = $targdpd / $rate; unset($targhpd);
	$days = $dolahead / $targdpd; unset($dolahead, $targdpd);
	$earnedTo = roint($now + $days * DAY_S); unset($now);
	
	$vars = get_defined_vars(); 

	return $vars;
    }



    private function findMarker(array $a) : array {
	foreach($a as $i => $c) {
	    if (!$c) continue;
	    if (trim($c) !== odsArrValCl::marker) continue;
	    $ret = array_slice($a, $i);
	    return $ret;
	}
	return [];
    }

    public function __construct(array $aa) {
	$this->do10($aa);
	$this->toDB();
    }

    private function toDB() {
	require_once(__DIR__ . '/db.php');
	odsDBCl::put($this->input);
    }

    private function do10(array $aa) {

	$ret = [];
	$db  = [];

	foreach($aa as $a) {
	    $t = $this->parse30($a);
	    if (!$t) continue;
	    $proj = $t['calcs']['project'];
	    $ret[$proj] = $t['calcs']; unset($t['calcs']);
	    $db [$proj] = kwam($t['uq'], $t['input']);
	}

	$this->hours = $ret;
	$this->input = $db;

	return;
    }



    private function parse30(array $aall) : array {
	$a = $this->findMarker($aall['all']);
	$dat = $this->getCalcsI($a);
	if (!$dat) return [];
	$uq = [];
	$uq['project'] = $aall['project'];
	$uq['Ufile'  ] = $aall['Ufile'];
	$input = $dat['input']; 
	unset   ($dat['input']);
	$ret = kwam($dat, $uq);
	return ['uq' => $uq, 'calcs' => $ret, 'input' => $input];
    }
 
}

if (didCLICallMe(__FILE__)) new odsFirstSheetCl();



