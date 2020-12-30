<?php 

/* The test is the following in "pack" mode: php flrand.php | rngtest
 * The test appears to be a failure for now, but outputting in binary mode is very helpful.
 * I will probably keep trying   */

$m10 = pow(10, 17); // I tried several values for this, trying to fill up a 64 bit unsigned int

$s = '';

if (1) 
for ($i=0; $i < 50; $i++) {
    $a[$i] = nanopk();
    if ($i === 0) continue;
    $rat = ($a[$i]['Uns'] - $a[$i - 1]['Uns']) / 
	   ($a[$i]['tsc'] - $a[$i - 1]['tsc']) ;
    
    if (1) {
	$rp3 = $rat * 1000;
    
	$r20 = $rp3 - round($rp3);
	$r25 = $r20;
    }
    
    $r30 = intval($r20 * $m10);
    
    if ($r30 === 0) { 
	$ignore = 0;
    }
    
    if ($r30 < 0) { 
	$ignore = 20;
    }
    
    if (0) echo($r30 . "\n");
    else if (1) echo(number_format($r30) . ' ' . decbin($r30) . "\n");
    else if (1) {
	$p = pack('Q', $r30);
	$l = strlen($p);
	for($j=0; $j < 8; $j++) echo($p[$j]);
    }
}
