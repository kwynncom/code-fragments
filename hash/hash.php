<?php

// enter memory as MB; PHP uses KB, not bytes, as the doc says
// that is, if you enter 100, that will run as 100 MB

require_once('/opt/kwynn/kwutils.php');

function password_hash_test() {
	
	global $argv;
	
	$phia = ['threads'     => $argv[1],
			'memory_cost' => $argv[2] * 1000,
			'time_cost'   => $argv[3]]; unset($argv);

	
	$pwd = base62(40);

	$b	  = microtime(1);
	$hash = password_hash($pwd, PASSWORD_ARGON2ID, $phia); unset($phia);
	$e	  = microtime(1);

	$elapf = $e - $b; unset($e, $b);
	$elaps = sprintf('%0.2f', $elapf);
	$all['exeSSt'] = $elaps; unset($elaps);
	$piao  = password_get_info($hash);
	$piaoo = $piao['options']; unset($piao);
	$piaoo['mem_mb'] = $piaoo['memory_cost'] / 1000;
	$all   = array_merge($all, $piaoo, ['hash' => $hash], ['pwdClear' => $pwd]); unset($hash, $piao, $pwd, $piaoo);
	$all['exeSFl'] = round($elapf, 2); unset($elapf);
	echo(json_encode($all) . "\n");
	unset($all);
	return;
}
password_hash_test();