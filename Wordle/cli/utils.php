<?php

define('F',   5);
define('Y', '1');
define('N', '0');
define('M', '+');
define('Z', ' ');

function isl(string $l) : bool { return preg_match('/^[a-z]{1}$/', $l) ? true : false; }

function cmp(string $a, string $b) : string {

    $r = '?????';

    for ($i=0; $i < F; $i++) {
	if ($a[$i] !== $b[$i]) continue;
	$r[$i] = Y;
	$a[$i] = $b[$i] = Z;
    }

    for ($i=0; $i < F; $i++) 
    for ($j=0; $j < F; $j++) {
	if (!isl($a[$i])) continue;
	if (!isl($b[$j])) continue;
	if ($a[$i] !== $b[$j]) continue;
	$r[$j] = M;
	$a[$i] = $b[$j] = Z;
    }

    for ($i=0; $i < F; $i++) if ($r[$i] === '?') $r[$i] = N;

    return $r;

}

