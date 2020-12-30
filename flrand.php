<?php

$m10 = pow(10, 19);

$s = '';

if (1) 
for ($i=0; $i < 50; $i++) {
    $a[$i] = nanopk();
    if ($i === 0) continue;
    $rat = ($a[$i]['Uns'] - $a[$i - 1]['Uns']) / 
	   ($a[$i]['tsc'] - $a[$i - 1]['tsc']) ;
    
    $rp3 = $rat * 1000;
    
    $r20 = $rp3 - round($rp3);
    $r25 = $r20; //  abs($r20);
    
    // $r30 = sprintf('%0.41f', $r25);
    
    $r30 = intval($r25 * $m10);
    
    // $r40 = sprintf('%020d', $r30);
    
    $s .= $r30 . "\n";
}

$p =   pack('Q', $s); // This just goes back and forth as one integer
$u = unpack('Q', $p);
for ($i=0; $i < count($u); $i++) echo($u[$i] . "\n");

if (0) for ($i=0; $i < 50; $i++) echo(sprintf('%0.50f', sqrt(2)) . "\n"); // PERFECTLY CONSISTENT!
