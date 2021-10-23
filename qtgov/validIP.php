<?php

require_once('/opt/kwynn/kwutils.php');

function validIPOrDie($ip) {
    static $ipv6re    = '/[0-9A-Fa-f:]+/';
    static $ipv4re    = '/\d+\.\d+\.\d+\.\d+/';
	
	$sl = strlen($ip);
	kwas($ip && is_string($ip) && $sl >= 7 && $sl <= 39, 'need an IP arg - 2');
	
	
	kwas(	 ($ip4m = preg_match($ipv4re, $ip))
		  || ($ip6m = preg_match($ipv6re, $ip))
			, 'bad IP preg'	);
	
	if ($ip4m) {
		// 123.456.789.012
		kwas($sl <= 15, 'ipv4 too big');
		$ip4 = ip2long($ip);
		kwas($ip4 && $ip4 > 0, 'ipv4 failed'); 
	}
	
	kwas(inet_pton($ip), 'inet_pton failed'); 
	
	return $ip;
}
