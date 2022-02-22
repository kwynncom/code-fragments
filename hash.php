#! /usr/bin/php
<?php // 2022/02/22

// enter memory as MB; PHP uses KB, not bytes, as the doc says
// that is, if you enter 100, that will run as 100 MB

require_once('/opt/kwynn/kwutils.php');

class password_hash {
	
	const dsize = 20;
	
public function __construct() {
	$this->doargs();
	$this->create();
}

function doargs() {
	global $argv;
	
	$ois = [ 1 => ['threads' => 2], 2 => ['memory_cost' => 1], 3 => ['time_cost' => 20]];
	$fopts = [];
	
	for($i=1; $i <= 3; $i++) {
		$t = kwifs($argv, $i);
		if ($t) $n = $t;
		else    $n = reset($ois[$i]);
		$k = key($ois[$i]); 
		if ($k === 'memory_cost') $n *= 1000;
		$fopts[$k] = $n;
	}

	$this->hopts = $fopts;
	
	if ($argp = kwifs($argv, 6)) $this->pwd = $argp;
	else						 $this->pwd = base62(self::dsize);
}
function create() {
	
	global $argv;

	$pwd = $this->pwd;
	
	$b	  = microtime(1);
	$hash = password_hash($pwd, PASSWORD_ARGON2ID, $this->hopts); unset($phia);
	$e	  = microtime(1);

	$elapf = $e - $b; unset($e, $b);
	$elaps = sprintf('%0.2f', $elapf);
	$all['exeSSt'] = $elaps; unset($elaps);
	$piao  = password_get_info($hash);
	$piaoo = $piao['options']; unset($piao);
	$piaoo['mem_mb'] = $piaoo['memory_cost'] / 1000;
	$all   = array_merge($all, $piaoo, ['hash' => $hash], ['pwdClear' => $pwd]); 
	$this->file($pwd, $hash);
	unset($hash, $piao, $pwd, $piaoo);
	$all['exeSFl'] = round($elapf, 2); unset($elapf);
	echo(json_encode($all) . "\n");
	unset($all);
	return;
}

private function file($p, $h) {
	global $argv;
	
	for ($i=4; $i <=5; $i++)
	if (isset($argv[$i])) {
		file_put_contents($argv[$i], '');
		chmod($argv[$i], 0600);
		file_put_contents($argv[$i], $i === 4 ? $h : $p . "\n");
	}
	
}
} // class

if (didCLICallMe(__FILE__)) new password_hash();