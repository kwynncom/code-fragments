<?php

declare(strict_types=1);

class odsArrValCl {

    const marker = 'autoKw18';
    const fs = ['marker', 'Ustart', 'hours', 'permonth', 'rate'];
    const maxInn  = 100;
    const maxOutn =   8;

    private static function getValidStart(string $hu) : int {
	$U = strtotime($hu); unset($hu);
	$now = time();
	kwas($U >= 1634387606 && $U <= $now, 'bad start time err # 083425');
	return $U;
    }

    private static function doDBRow(array $r) : array {
	$vf = self::fs;
	unset($vf['marker']);
	$vf[] = 'Ufile';
	$vf[] = 'project';
	$rcp = $r;
	foreach($rcp as $f => $vignore) {
	   if (!in_array($f, $vf)) unset($r[$f]);
	}

	return $r;
    }

    public static function getValid(array $ain) : array {

    	kwas($ain && is_array($ain), 'val fail # 201112');

	if (!isset($ain[0])) return self::getValidOneRow(self::doDBRow($ain));

	$n = count($ain);
	kwas($n <= self::maxInn, 'array too big err # 201215'); unset($n);
	kwas(isset($ain[0]), 'bad array err # 205524');

	kwas($ain[0] === self::marker, 'array bad err # 080811');
	$a = array_slice($ain, 0, count(self::fs)); unset($ain);
	kwas(count($a) === count(self::fs), 'bad array n count err # 203731');

	$can = [];

	for($i=1; $i < count(self::fs); $i++) {
	    $k = self::validWOrDie(self::fs[$i]);
	    $v = $a[$i];
	    switch($k) {
		case 'Ustart' : $can[$k] = self::getValidStart($v); break;
		case 'hours' : $can[$k] = self::getValidHours(floatval($v)); break;
		case 'rate'  : case 'permonth' : $can[$k] = self::getIntOrFl($v); break;
		default      : kwas(false, 'unknown key to array err # 201936'); break;
	    }

	    kwas(isset($can[$k]), "key $k not set err # 202139");
	}

	kwas(count($can) === count(self::fs) - 1, 'bad array count err # 204744');
	kwas(count($can) <= self::maxOutn, 'array too big err # 201215'); 

	$vret = self::getValidOneRow($can); unset($can);
	return $vret;

	
    }

    private static function getValidHours(float $hin) : float {
	kwas(is_float($hin), 'bad hours input err # 201846');
	return $hin;
	

    }

    public static function getIntOrFl($vin) : int | float {
	$fl = floatval($vin);
	$iv = roint   ($vin);
	if (abs($fl - $iv) < 0.001) return $iv;
	return $fl;
    }

    public static function getValidAProj(array $a) : array {

	$ret = [];

	foreach($a as $proj => $a) {
	    kwas($proj && is_string($proj), 'bad project val (err # 071718 )');
	    self::validWOrDie($proj);


	    $ret[$proj] = self::getValidOneRow($a);
	}

	return $ret;
    }

    public static function getValidOneRow(array $a) : array {

	$ret = [];

	foreach($a as $k => $v) {
	    self::validWOrDie($k);
	    if (!(is_float($v) || is_integer($v) || self::validWOrDie($v)))
		kwas(false, 'data val fail err # 073646');
	    $ret[$k] = $v;
	} unset($a, $k, $v);

	return $ret;
    }

    private static function validWOrDie(int | string $s) : string {
	if (is_integer($s)) $s = (string)$s;
	kwas($s && trim($s) && strlen($s) <= 20, 'string fail (err # 071931 )');
	kwas(preg_match('/^[A-Za-z0-9]{1,20}$/', $s), 'string preg fail (err # 071831 )');
	return $s;
    }

}

