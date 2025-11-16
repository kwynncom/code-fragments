<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php'); // only forces warnings to fatal errors, so far

define('F',      5 );
define('U', F -  1 );
define('Y',     '1');
define('N',     '0');
define('M',     '+');
define('P',     '?');
define('I', str_pad('', F, P));
define('D', '!@#$%^&*()');

function cmp(string $s, string $g) : string {

    $r = I;
    $q = 0;

    for ($j=0; $j < F; $j++) {
	if ($g[$j] !== $s[$j]) continue;
	$r[$j] = Y;
	$s[$j] = D[$q++];
	$g[$j] = D[$q++];
    }

    for ($j=0; $j < F; $j++) {	
	for ($i=0; $i < F; $i++) {
	    if ($g[$j] ===      $s[$i]) {
		$r[$j] = M;
		$g[$j] = D[$q++];
		$s[$i] = D[$q++];
	    }
	} // for

	if ($r[$j] === P) $r[$j] = N;

    } // for

    return $r;
} // func