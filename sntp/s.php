<?php

$ps = ['amazon'];

foreach($ps as $p) 
	for ($i=0; $i < 1; $i++) {
		$d = $i . '.' . $p . '.pool.ntp.org';
		$r = shell_exec('nslookup ' . $d);
		echo($r);
	}
	
