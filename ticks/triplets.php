<?php

$i = 0;

$outr = fopen('/dev/null', 'w');

$r = [];
do {
    for($j=0; $j < 3    ; $j++) $r[$j] = nanopk();
    for($j=0; $j < 3 - 1; $j++) $s[$j] = $r[$j+1]['Uns'] - $r[$j]['Uns'];
    sort($s);
    $d = $s[1];
    fwrite($outr, $d . "\n");
    if ($i < 4) continue;
    $outr = STDOUT;
} while($i++ < 40);