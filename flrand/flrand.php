<?php // rngtest gives this version a grade of 50 - 70%

$m10 = pow(10, 20); 

$s = '';

if (1) 
for ($i=0; $i < 30000; $i++) {
    $a[$i] = nanopk();
    if ($i === 0) continue;
    $rat = ($a[$i]['Uns'] - $a[$i - 1]['Uns']) / 
	   ($a[$i]['tsc'] - $a[$i - 1]['tsc']) ;
    
    $r10 = sprintf('%0.50f', $rat);
    $r20 = intval(substr($r10, 8, 11));
    $r30 = $r20 % (1 << 34);
   
    if ($r30 < pow(2, 28)) continue;
    
    // echo $r30 . "\n";
    
    $s .= pack('l', $r30);
}

echo $s;

