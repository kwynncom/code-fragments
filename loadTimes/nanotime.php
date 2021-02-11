<?php

if (1) for($i=0; $i < 10; $i++) {
    $ns = nanotime();
    nanotime_array();
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
    nanotime_array();
    $n1 = nanotime();
    $d = $n1 - $n0;
    $dd = number_format($d);
    echo($dd . "\n");


}
