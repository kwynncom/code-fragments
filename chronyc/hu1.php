<?php

require_once('config.php');

class chrony_parse {

    public static function toArray(string $cin) { 
        $anl = explode("\n", $cin); 
	$a = [];
	foreach($anl as $row) {
	    $ac = explode(' : ', $row);
	    if (!$ac || count($ac) !== 2) continue;
	    if (   trim($ac[0]) &&  trim($ac[1]))
		$a[trim($ac[0])] =  trim($ac[1]);
	}
	
	return $a;    
    }   
    
    public static function parse(string $sin) {
	$a = self::toArray($sin);
	$o = new self();
	$o->p10($a);
	return;
	
    }
    
    private function p10($a) {
	
	$pd = self::ago($a);

	$pds = sprintf('%0.1f', $pd / 60); unset($pd);
	$pdd = $pds . 'mago'; unset($pds);
	
	$os = self::off($a);
    
	$fs = sprintf('%+0.1f', $os * M_MILLION); unset($os);
	$tdd = $fs . 'uso'; unset($fs);

	$fd = self::freq($a);
	
	echo($tdd . ' ' . $pdd . ' ' . $fd);
	
    }
    
    public static function freq($a) {
	// Frequency       : 7.634 ppm slow

	preg_match('/(^\d+\.\d+) ppm (\w+)/', $a['Frequency'], $matches); 	
	
	$sign = '?';
	if      ($matches[2] === 'fast') $sign = '+';
	else if ($matches[2] === 'slow') $sign = '-';

	return $sign . $matches[1];	
    }
    
    public static function ago($a) {
	$hd = translateHost($a['Reference ID']);
	if ($hd === 'kwynn.com') $hd = '';
	if ($hd) 	echo($hd . "\n");
	$key = 'Ref time (UTC)';
        $ts = strtotime($a[$key] . ' UTC');
	$d  = time() - $ts;	
	return $d;
    }
    
    public static function off($a) {
	$st = $a['System time'];
    
	preg_match('/(^\d+\.\d+) seconds (\w+) of NTP time/', $st, $matches); unset($st); kwas(isset($matches[2]), 'regex fail offset'); 
    
	$s = $matches[1];

	$sign = '?';
	if      ($matches[2] === 'fast') $sign = '+';
	else if ($matches[2] === 'slow') $sign = '-';

	return $sign . $s;
    }
}