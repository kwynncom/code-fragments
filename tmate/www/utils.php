<?php

function mkdir_safe(string $dir, int $perm = 0770) {
	if (file_exists($dir)) return;
	// echo($dir . "attempt \n");
	umask(0);
	kwas(mkdir($dir, $perm, true), "$dir create failed - mkdir_safe - 1917");
}

function tmate_get_fn(string $pre = '', int $U = 0, array $geo = [], $asa = false) : string | array {
	
	if ($U === 0) $U = time();
	
	$hu  = date(tmate_config::hu, $U);
	$f = '';
	$i = 0;
	foreach(['city', 'region', 'country'] as $k) {
		$t = kwifs($geo, $k, ['kwiff' => '']);
		if (!$t) continue;
		if ($i++ === 0) $f .= '-';		
		$f .= '-' . $t;
	}	
	
	$f = $hu . $f;
	$path = $pre . $f;
	
	if ($asa) return ['path' => $path, 'hu' => $f];
	return $path;
	
	
}

function tmate_get_vinord(string $tin = '') : string {
	if ($tin) $t = $tin; 
	else      $t = file_get_contents('php://input'); unset($tin);
	kwas($t && is_string($t) && strlen($t) <= tmate_config::maxstrlen, 'bad input - tmate server receive'); 
	kwas(tmate_get_hash($t), 'cannot get hash from ssh - utils edition - get_ - 1336');
	return $t;
}

function tmate_get_hash(string $t) : string {

	kwas(preg_match(tmate_config::resrw, $t, $m), 'did not get valid ssh');	
	
	kwas(isset($m[1]), 'tmate ssh hash fail - 1328');
	$p = $m[1]; kwas($p && is_string($p) && strlen($p) >= tmate_config::minsshklen, 'bad key tmate hash - 1331'); unset($m);
	kwas(preg_match(tmate_config::shksare, $p), 'bad final check hash input 1333'); 
	$hash = hash('sha256', $p);
	$l = strlen($hash); 
	kwas($l === 64, 'bad hash tmate get - 1342');
	return $hash;	
}