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

function cmp(string $s, string $g) : string {

    $r = I;

    for ($i=0; $i < F; $i++) {	
    for ($j=0; $j < F; $j++) {
	if ($r[$j] === Y     ) { continue; }
	if ($g[$j] === $s[$i]) { $r[$j] = $i === $j ? Y : M; continue; }
	if ($i < U)	       { continue;   }
	if ($r[$j] === P)      { $r[$j] = N; }  
    } // for
    } // for

    return $r;
}