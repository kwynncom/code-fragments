<?php

$version = 'blah 2 asfasfasfsdf';

$iter = 20;
$i = 0;
$r = [];
$s = [];
$v = $d = PHP_INT_MAX;
$min = PHP_INT_MAX;
$com = [];
$ret = [];

do {
    for($j=0; $j < 3    ; $j++) $r[$j] = nanopk();
    for($j=0; $j < 3 - 1; $j++) $s[$j] = $r[$j+1]['Uns'] - $r[$j]['Uns'];
    sort($s);
    $v = $s[1];
    $com['pk']  = $r;
    $com['maxd'] = $v;
    $com['iter'] = $iter;
    $com['Uns' ] = $r[1]['Uns'];
    if ($v < $min) $ret = $com;
    $d = $v . "\n";
    if ($i < 2) continue;
//    echo($d);
} while($i++ < $iter);

var_dump($com);
