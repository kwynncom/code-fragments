<?php

require_once('/opt/kwynn/kwutils.php');

class machine_id {
    
    const minStrlenW = 7;
    
    const idBase = '/sys/class/dmi/id/';
    const idFileInit  = 'chassis_vendor';
    const idFilesU    = ['product_name', 'product_serial'];
    const idFilesReal = ['product_uuid'];
    const idFilesAWS  = ['board_asset_tag'];
    const idPublic    = ['chassis_vendor', 'product_name', 'board_asset_tag'];
    
    const outBase   = '/tmp/';
    const ofPrivate = 'midpr';
    const ofPublic  = 'midpu';
    const ofsfx     = '_namespace_kwynn_com_2020_1213_mid_1';
    
    const midv = 'v0.0.2 - 2020/12/13 9:37pm EST GMT -0500';
    
    const testUntil = '2015-12-13 19:10';
    
    private static function isTest() {
	return time() < strtotime(self::testUntil);
    }
    
    public static function get() {
	$a = self::get20();
	self::out($a);
    }
    
    private static function isPublic($fin) { return in_array($fin, self::idPublic);   }
    
    private static function out($ain) {
    	self::outPrivate($ain);
	$r = [];
	foreach($ain as $k => $v) {
	    $fn = pathinfo($k, PATHINFO_FILENAME);
	    if (self::isPublic($fn)) $r[$k] = $v;
	}
	
	$r['mid'] = hash('sha256', $ain['private_string']);
	$r['midv'] = self::midv;
	$now = time();
	$r['at'] = $now;
	$r['atr'] = date('r', $now);
	$p = self::outBase . self::ofPublic . self::ofsfx;
	
	$json = json_encode($r);
	kwas(file_put_contents($p, $json) === strlen($json), 'public file_put failed machine_id');
	kwas(chmod($p, 0444), "chmod public failed on $p - machine_id out()");
	echo('OK - files written' . "\n");
	var_dump($r);
    }
    
    private static function outPrivate($ain) {
	$prf = self::outBase . self::ofPrivate . self::ofsfx;
	if (file_exists($prf)) kwas(unlink($prf), "cannot delete existing $prf - machine_id outPrivate()");
	touch($prf);
	kwas(chmod($prf, 0600), "machine_id chmod failed out()");
	$out = $ain['private_string'];
	kwas(file_put_contents($prf, $out) === strlen($out), 'file_put private failed - machine_id');
	chmod($prf, 0400);
    }
    
    private static function get20() {
	$a  = array_merge(self::idFilesU);
	$ma = self::getMin(self::idFileInit);
	if ($ma['s'] === 'Amazon EC2') $m = self::idFilesAWS;
	else			       $m = self::idFilesReal;
	$a = array_merge($a, $m);
	$s = $ma['s'] . ' ';
	$r[$ma['p']] = $ma['s'];
	
	foreach($a as $f) {
	    $ma = self::getMin($f);
	    $s  .= $ma['s'] . ' ';
	    $r[$ma['p']] = $ma['s']; 
	}
	$s = trim($s);
	$r['private_string'] = $s;
	return $r;
    }
    
    private static function getMin($fin) {
	
	if (self::isTest() && !self::isPublic($fin)) return ['s' => 'test only', 'p' => $fin];
	
	$p = self::idBase . $fin;
	$s = trim(file_get_contents($p));
	$st = preg_replace('/\W/', '', $s);
	$re = '/^[\w]{' . self::minStrlenW . '}/';
	kwas(preg_match($re, $st, $m), "$st failed min character requirements - machine_id::getMin()");
	return ['s' => $s, 'p' => $fin];
    }


}

machine_id::get();
