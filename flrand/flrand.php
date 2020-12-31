<?php /* $ php flrand.php | rngtest
 *  Results in 40 - 85% success */

$m10 = pow(10, 20); 

$s = '';

if (1) 
for ($i=0; $i < 150000; $i++) {
    $a[$i] = nanopk();
    if ($i === 0) continue;
    $rat = ($a[$i]['Uns'] - $a[$i - 1]['Uns']) / 
	   ($a[$i]['tsc'] - $a[$i - 1]['tsc']) ;
    
    $r10 = sprintf('%0.50f', $rat);
    $r20 = intval(substr($r10, 7, 17));
    $r30 = $r20 % (1 << 33);
   
    if ($r30 < pow(2, 28)) continue;
    
    // echo $r30 . "\n";
    
    $s .= pack('l', $r30);
}

echo $s;

