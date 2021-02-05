<?php

if (0) for($i=0; $i < 10; $i++) {
    $ns = nanotime();
    if ($i === 0) {
	$pns = $ns;
	continue;
    }
    $d = $ns - $pns;
    $dd = number_format($d);
    echo($dd . "\n");
    $pns = $ns;
}

for($i=0; $i < 10; $i++) {
    $n0 = nanotime();
    $n1 = nanotime();
    $d = $n1 - $n0;
    $dd = number_format($d);
    echo($dd . "\n");


}
