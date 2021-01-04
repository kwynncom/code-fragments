<?php

require_once('cpu_config.php');
require_once('/opt/kwynn/kwutils.php');

function getStableNanoPKByModel($maxiter = 20, $ranges = []) {
    
    $v = $d = $min = PHP_INT_MAX;
   
    for($i=0; $i < $maxiter; $i++) {
	for($j=0; $j < 3; $j++) $r[$j] = nanopk();
	for($j=0; $j < 2; $j++) $s[$j] = $r[$j+1]['Uns'] - $r[$j]['Uns'];
	sort($s);
	$v = $s[1];
	$t = $r[1];
	$t['maxd'] = $v;
	$t['maxi'] = $maxiter;
	$t['i']    = $i;
	if ($v < $min) $ret = $t;
	$rmd = $ret['maxd'];
	
	foreach($ranges as $rg)
	    if ($i > $rg['i']) if ($rmd < $rg['m']) return $ret;
	
    }
    
    return $ret;
}

function getStableNanoPK() {
    $res = getStableNanoPKByModel(100000, cpu_tick_config());
    return $res;
}

if (didCLICallMe(__FILE__)) {
    $res = getStableNanoPK();
    echo($res['i'] . "\n");
    var_dump($res);
}
