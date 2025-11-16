<?php

define('F',      5 );
define('U', F -  1 );
define('Y',     '1');
define('N',     '0');
define('M',     '+');
define('P',     '?');
define('I', str_pad('', F, P));

function cmp(string $a, string $b) : string {

    $r = I;

    for ($i=0; $i  < F ;  $i++) {	
    for ($j=0; $j  < F ;  $j++) {

	    if ($r[$j] !== P	 ) {				 continue; }
	    if ($b[$j] === $a[$i]) { $r[$j] = $i === $j ? Y : M; continue; } 
	    if (   $i  === U	 ) { $r[$j] =		      N; continue; }   

    } // for
    } // for 

    return $r;
}
