<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/../ticks/stddev.php');

$wsig = file_get_contents('/tmp/hwrset1.wav');
$l = strlen($wsig);
// kwas($l === 3840044, 'bad length');

$wsig = substr($wsig, 44); // head rid of header
$l -= 44;

$min = $minl = PHP_INT_MAX;
$sto = false;

for($i= (384000) * 20 + (3840 * 4 * 5); $i < $l - 8; $i += 8) {
    for($j=0; $j < 1; $j++) {
	$byte = $i + $j * 4;
	$subs = substr($wsig, $byte, 4);
	$u = unpack('l', $subs);
	$isigraw = $u[1];
	$is10 = $isigraw < 0 ? -$isigraw : $isigraw;
	$isl = log($is10);
	$sec = ($i / 384000);
	$ti = intval(floor($sec * 10));
	doStats($sto, $ti, $isl);
    }
}

foreach($sto as $i => $o) {
    
    $a = $o->get();
    $av = $a['a'];
    $ad = sprintf('%0.1f', round($av, 1));
    $s  = '';
    $s .= $i . ' ';
    $s .= $ad;
    // $s .= sprintf('%e', (round($av)));
    // $s .= number_format($a['a']);
    $s .= ' ';
    
    // $s .= $i;
    // $s .= ' ';
    // $s .= $ad;
    $s .= ' ';
    // $s .= sprintf('%0.2f' , $a['s']);
    $s .= "\n";
    echo($s);
    
}

exit(0);

function doStats(&$stin, $iin, $din) {
    
    static $thisi = 0;
    static $thiss = 0;
   
    if ($din < 0.001) return;     // for filtering weird logs //  
    if (!isset($stin[$iin])) $stin[$iin] = new stddev();
    if (0 && isset(   $stin[$iin - 1])) {
	$a = $stin[$iin - 1]->get();
	$thiss += $a['s'];
	if ($thisi++ === 1000) {
	    echo(number_format($thiss) . "\n");
	    exit(0);
	}
	
    }
    $stin[$iin]->put($din);
}