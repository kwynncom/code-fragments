<?php

require_once('utils.php');

define('F',   5);
define('Y', '1');
define('N', '0');
define('M', '+');
define('Z', ' ');


class w {

    public static function cmp(string $a, string $b) : string {

	$r = '?????';

	for ($i=0; $i < F; $i++) {
	    if ($a[$i] !== $b[$i]) continue;
	    $r[$i] = Y;
	    $a[$i] = $b[$i] = Z;
	}

	for ($i=0   ; $i < F - 1; $i++) 
	for ($j=$i+1; $j < F    ; $j++) {
	    if (!isl($a[$i])) continue;
	    if ($a[$i] !== $b[$j]) continue;
	    $r[$j] = M;
	    $a[$i] = $b[$i] = Z;
	}

	for ($i=0; $i < F; $i++) if ($r[$i] === '?') $r[$i] = N;
	
	return $r;

    }

}

echo(w::cmp('arise', 'blank') . "\n");
echo(w::cmp('arise', 'trash') . "\n");
echo(w::cmp('arise', 'quote') . "\n");



