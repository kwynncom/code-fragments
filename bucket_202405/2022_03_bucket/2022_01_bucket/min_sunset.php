<?php

$loc = ['lat' => 34.249685, 'lon' =>  -84.140483, 'name' => 'Sawnee Mountain Preserve, Cumming, GA, USA']; 
$az = 90.833333; // sunrise
// $az = 108; // astronomical twilight

$now = time();

$res = [];
for ($i=-10; $i < 35; $i++) {

    $forts = strtotime($i . ' days', $now);
    $gmto = -5 + date('I', $forts);
    $risets = date_sunset($forts, SUNFUNCS_RET_TIMESTAMP, $loc['lat'], $loc['lon'], $az, $gmto);
    $rises  = intval(date('s', $risets));
    $risefo = date_sunset($forts, SUNFUNCS_RET_DOUBLE   , $loc['lat'], $loc['lon'], $az, $gmto);
    $tmpa['hrfl'] = $risefo;
    $hrfr  = $risefo - intval(date('g', $risets));
    $sfl   = 3600 * $hrfr;
    $msfr = $sfl - date('i', $risets) * 60 - $rises;

    $risemsd1 = intval(floor($msfr * 100));
    $risemsd  = sprintf('%02d', $risemsd1);
    
    $risefr = sprintf('%0.5f', $risefo);
    $sss = date('g:i:s', $risets) . '.' . $risemsd . ' ' . date('A', $risets);
    $dates = ' on ' . date('D, M d', $forts);

    $tmpa['d'] = $sss . $dates;
    $res[$i] = $tmpa;
}

$d = '';
for ($i=-10; $i < 35; $i++) {
     $d .= $res[$i]['d'];
     if (isMin($res, $i)) $d .= ' *** MIN / EARLIEST SUNSET ***';
     $d .= "\n";
}

echo $d;

function isMin($a, $i) {
    for($j=-1; $j <= 1; $j++) if (!isset($a[$i+$j])) return false;
    if (	$a[$i-1]['hrfl'] > $a[$i]['hrfl']
	    &&	$a[$i+1]['hrfl'] > $a[$i]['hrfl']) return true;
    return false;
    
    
}