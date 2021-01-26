<?php

require_once('config.php');

class chrony_parse {

    const shown = 10;
    
    public static function parse(array $biga) {
	$o = new self();
	$o->p10($biga);
	return;
	
    }

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
    
    private function p10($biga) {
	
	echo(self::head() . "\n");
	
	for($i=self::shown; isset($biga[$i]); $i--) {
	    $this->p20(self::toArray($biga[$i]['ch']));
	}
    }
    
    private function p20($a) {
	
	$pd = self::ago($a);
	$pds = sprintf('%0.1f', $pd / 60); unset($pd);
	$pdd = sprintf('%4s', $pds); unset($pds);
	
	$os = self::off($a);
    
	$fsf = sprintf('%+0.1f', $os * M_MILLION); unset($os);
	// $fds = sprintf('%');
	$tdd = $fsf; unset($fsf);

	$fd = self::freq($a);
	
	$rdd  = self::root20($a['Root dispersion']);
	$rfd  = self::freq20($a['Residual freq']);
	$skd  = self::freq20($a['Skew']);
	$rde  = self::root20($a['Root delay']);
	$rdde = ' ' . intval(round($rde)); unset($rde);
	
	unset($a);
	$vars = get_defined_vars();

	foreach($vars as $v) echo($v . ' ');
	echo("\n");
    }
 
    private static function head() {
	return 'mago  uso    f     rdi     rf   sk  rde';
    }
    
    private static function freq20($din) {
	$rfr = $din;
	$rf  = trim(preg_replace('/[^\d\.\-]/', '', $rfr));
	return $rf;
    }
   
    private static function root20($din) {
	$rdr = $din;
	$rd = trim(preg_replace('/[^\d\.]+/', '', $rdr));
	$ms = $rd * 1000;
	$rdd = sprintf('%0.3f', $ms); 
	return $rdd;
    }
    
    public static function freq($a) {
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