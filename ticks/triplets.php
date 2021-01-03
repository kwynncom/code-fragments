<?php

function getStableNanoPK() {
    $iter = 20;
    $i = 0;
    $v = $d = $min = PHP_INT_MAX;

    do {
	for($j=0; $j < 3; $j++) $r[$j] = nanopk();
	for($j=0; $j < 2; $j++) $s[$j] = $r[$j+1]['Uns'] - $r[$j]['Uns'];
	sort($s);
	$v = $s[1];
	$com['ck']  = $r;
	$com['d'] = $r[1];
	$com['m']['maxd'] = $v;
	$com['m']['iter'] = $iter;
	$com['m']['Uns' ] = $r[1]['Uns'];
	if ($v < $min) $ret = $com;
    } while($i++ < $iter);
    
    unset($ret['ck']);
    
    return $ret;
}
// var_dump(getStableNanoPK());
