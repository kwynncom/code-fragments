<?php

require_once('/opt/kwynn/kwutils.php');

function getStableNanoPK($pkonly = false) {
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
	usleep(random_int(1, 50)); // makes things much "worse"
    } while($i++ < $iter);
    
    unset($ret['ck']);
    
    if ($pkonly) return $ret['d'];
    
    return $ret;
}

if (didCLICallMe(__FILE__)) {
    $res = getStableNanoPK();
    echo($res['m']['maxd'] . "\n");
}
