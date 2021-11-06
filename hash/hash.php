<?php

	

	$a = [  'time_cost'      => $argv[1],
			'threads'        => $argv[2],
			'memory_cost_mb' => $argv[3]];
	
	$a['pass'] = $pwd = trim(shell_exec('base62 40'));

	$b = microtime(1);
	$a['hash'] = password_hash($pwd, PASSWORD_ARGON2ID, $a);
	$e = microtime(1);

	$elapf = $e - $b;
	$elaps = sprintf('%0.2f', $elapf);
	$c['secsExeS'] = $elaps;
	$c = array_merge($c, $a);
	$c['execsExeF'] = round($elapf, 2);
	echo(json_encode($c) . "\n");
	
	
	
	