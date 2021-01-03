<?php

require_once('/opt/kwynn/kwutils.php');

function getStableNanoPK($maxiter = 20, $ranges = false) {
    
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
	
	if ($ranges) {
	    if ($i >  5 && $rmd <= 470) break;
	    if ($i > 10 && $rmd <= 478) break;
	    if ($i > 20 && $rmd <= 526) break;
	}
    }
    
    return $ret;
}

if (didCLICallMe(__FILE__)) {
    $res = getStableNanoPK();
    echo($res['i'] . "\n");
    var_dump($res);
}
