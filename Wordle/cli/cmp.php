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
define('D', '!@#$%^&*()`~<>{}[]|,.;:|-_"\'');

function cmp(string $s, string $g) : string {

    $r = I;
    $q = 0;

    for	    ($j=0; $j < F; $j++) {	
	for ($i=0; $i < F; $i++) 
	{
	    if  ($r[$i] === Y) continue;
	    if  ($r[$j] === M) continue;

	    $y = $g[$i] === $s[$i]; 
	    $m = $g[$j] === $s[$i];
	    if (!$y && !$m) continue; unset($m);

	    $k =     $y ? $i : $j;
	    $r[$k] = $y ?  Y :  M;
	    $g[$k] = D[$q++];
	    $s[$i] = D[$q++];

	} // inner for

	if ($r[$j] === P) $r[$j] = N;

    } // outer for

    return $r;
} // func