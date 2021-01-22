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
	echo translateHost($a['Reference ID']);
	return;
	
    }
}