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

    for	    ($i=0; $i < F; $i++) 
    {	for ($j=0; $j < F; $j++) {
	    if  ($r[$j] === Y)	    { break;	}
	    if  ($r[$i] === M)	    { continue; }

	    $y = $g[$j] === $s[$j]; 
	    $m = $g[$i] === $s[$j];

	    if (!$y && !$m)	    { continue; }

	    $k =     $y ? $j : $i;
	    $r[$k] = $y ?  Y :  M;
	    $g[$k] = D[$q++];
	    $s[$j] = D[$q++];

	} // inner for

	if ($r[$i] === P) { $r[$i] = N; }

    } // outer for

    return $r;
} // func