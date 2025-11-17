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
    {

    

	if ($r[$i] === Y) { 
	    continue; /* agate slave 00101 */

	}

	for ($j=0; $j < F; $j++) {
	    if  ($r[$j] === Y)	    { 
		continue; /* can happen: above borne ++001 */
	    }

	    $y = $g[$j] === $s[$j]; // green

			 //    not yellow
	    if (!$y && ($g[$i] !== $s[$j]))  { continue; }

	    $k =     $y ? $j : $i;
	    $r[$k] = $y ?  Y :  M;
            $g[$k] = D[$q++];
            $s[$j] = D[$q++];

	    if (!$y) break;
	    if ($i === $j) {
		continue 2;
	    }

	} // inner for

	if ($r[$i] === P) { $r[$i] = N; }

    } // outer for

    return $r;

} // func