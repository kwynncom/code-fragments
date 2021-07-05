#! /usr/bin/php
<?php

class iping {

	const maxTries    = 200;
	const okIfWithinS =   3; // success if both 4 and 6 are successful within that many seconds

	public static function doit() { 
		if (self::doitI10()) 
			 echo("** BOTH OK ***\n");
		else die('gave up after ' . self::maxTries . "\n");
	}

	public static function doitI10() { // I for internal, 10 so that I can create 05 or 20, like 1980s BASIC lines

		$ipvs = [4, 6];
		
		$gotat    = [];
		$gotat[4] = -1; // see README note "$gotat-init"
		$gotat[6] = PHP_INT_MIN + abs($gotat[4]); // same - see note

		$i   = 0;
		
		while($i++ <= self::maxTries) {
			foreach($ipvs as $ipv) {
				echo("IPv$ipv: ");
				$cmd = $ipv . ' ' . ' -c 1 kwynn.com' . ' | ' . 'head -n 2';
				$res = shell_exec($cmd);
				echo $res;
				if (preg_match('/\d+ bytes from/', $res)) { 
					echo("** IPv$ipv OK *****\n");
					$gotat[$ipv] = time();
					if (abs($gotat[4] - $gotat[6]) < self::okIfWithinS) return true;
				} else sleep(1);
			}
		}
	} // doit()
} // class

iping::doit();
