<?php // seems to fail any way

$m10 = pow(10, 20); 

$s = '';

if (1) 
for ($i=0; $i < 5000; $i++) {
    $a[$i] = nanopk();
    if ($i === 0) continue;
    $rat = ($a[$i]['Uns'] - $a[$i - 1]['Uns']) / 
	   ($a[$i]['tsc'] - $a[$i - 1]['tsc']) ;
    
    if (0) {
	$rp3 = $rat * 1000;
    
	$r20 = $rp3 - round($rp3);
	$r25 = $r20;
    } else $r20 = $rat;
    
    $r30 = intval($r20 * $m10);
    
    if ($r30 === 0) { 
	$ignore = 0;
    }
    
    if ($r30 < 0) { 
	$ignore = 20;
    }
    
    $r40 = decbin($r30);
    
    if (1) {
	$b10 = trim(substr($r40, 1, 32));
	if (strlen($b10) !== 32) {
	    $ignore = 3;
	}
	
	$r50 = bindec($b10);
	
	if ($r50 === 0) {
	    $ignore = 4;
	}
	
	// echo($r50 . "\n");
    }
    else if (0) echo($r30 . "\n");
    else if (1) echo(number_format($r30) . ' ' . decbin($r30) . "\n");
    else if (1) echo(decbin($r30) . "\n");
    
    if (1) {
	$p = pack('L', $r50);
	$l = strlen($p);
	for($j=0; $j < 4; $j++) echo($p[$j]);
    }
}
