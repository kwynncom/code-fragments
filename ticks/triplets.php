<?php

require_once('cpu_config.php');
require_once('/opt/kwynn/kwutils.php');

function getStableNanoPKByModel($maxiter = 50, $ranges = []) {
    
    $v = $minfound = PHP_INT_MAX;
   
    for($i=0; $i < $maxiter; $i++) {
	$r = nanopkavg();
	$v = $r['Unsdif'];
	$t = $r;
	$t['i']    = $i;
	if ($v < $minfound) {
	    $ret = $t;
	    $minfound = $v;
	}

	foreach($ranges as $rg)
	    if ($i > $rg['i']) if ($ret <= $rg['v']) return $ret;
    }
    
    return $ret;
}

function getStableNanoPK() {
    $res = getStableNanoPKByModel(200, cpu_tick_config());
    return $res;
}

if (didCLICallMe(__FILE__)) {
    $res = getStableNanoPK();
    echo($res['i'] . "\n");
    var_dump($res);
}
