<?php

require_once('/opt/kwynn/kwutils.php');

$wsig = file_get_contents('/tmp/hwrset1.wav');
$l = strlen($wsig);
kwas($l === 3840044, 'bad length');

$wsig = substr($wsig, 44); // head rid of header
$l -= 44;

$min = $minl = PHP_INT_MAX;

for($i= 384000 * 2; $i < $l - 8; $i += 8) {
    for($j=0; $j < 2; $j++) {
	$byte = $i + $j * 4;
	$subs = substr($wsig, $byte, 4);
	$u = unpack('l', $subs); // I am unpacking as signed versus unsigned
	$isigraw = $u[1];
	// $isig = fsig($isigraw);
	$is10 = $isigraw < 0 ? -$isigraw : $isigraw;
	if ($isigraw < $min) $min = $isigraw;
	$isl = log($is10);
	if ($isl < $minl && $isl > 0) $minl = $isl;
	// $isd = f20($isl);
	$isd = $isl;
	$sec = ($i / 384000);
	$deg = intval(round((21600000 / $sec))) % 360;

	$sd  = sprintf('%0.2f', $sec);
	$dd  = sprintf('%03d' , $deg);
	$bd  = sprintf('%07d', $byte);
	echo($bd . ' ' . $j . ' ' . $dd . ' ' . $sd . ' ' . $isd . "\n");    
    }
}

echo('min = '. $min . "\n");
echo('min log = '. $minl . "\n");

exit(0);

function fsig($sin) {
    
    static $min  = -35691520;
    static $pmin =  35691520 + 1;
    
    if ($sin < $min) $sin = 0;
    $sin += $pmin;
    
    return $sin;
}

function f20($sin) {
    
    $s10 = $sin - 15.179739508624;
    $s20 = $s10 * 1000;
    $s30 = intval(round($s20));
    return $s30;
}