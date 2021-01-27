<?php

require_once('/opt/kwynn/kwutils.php');
// require_once(__DIR__ . '/../ticks/batch1/stddev.php');

$file = '/tmp/r3_ant.wav';

$bytesPerSam = 4;
$channels = 2;
$sampleRate = 48000;
$bitsPerSam = $bytesPerSam * 8;
$duration = 15;

$useBytes = $bytesPerSam;

if      ($bytesPerSam === 4) $packf = 'l';
else if ($bytesPerSam === 2) $packf = 'S';

$cmd = 'arecord -f S' . $bitsPerSam . '_LE -c ' . $channels . ' -r ' . $sampleRate . ' --device="hw:0,0" -d ' . $duration . ' > ' . $file;

// arecord -f S16_LE -c 2 -r 8000 --device="hw:0,0" -d 2 > /tmp/hwrset1.wav
if (1) {
kwas(unlink($file), 'delete failed');
exec($cmd);
}

$wsig = file_get_contents($file);
$l = strlen($wsig);
$bpsec = $channels * $bytesPerSam * $sampleRate;
$header = 44;

kwas($l === $bpsec * $duration + $header, 'bad length');

$wsig = substr($wsig, 44); // get rid of header
$l -= 44;

$min = $minl = PHP_INT_MAX;
$sto = false;

$soffhun = 8;
$spp60kHz = 0.000016667;
$peroff = 0.2;
$soffFromFileStart = 2;
$secsoff = $spp60kHz * $peroff + $soffhun / 100 + $soffFromFileStart;
$bytesoffraw = intval(round($bpsec * $secsoff));

for($i=0; $i < $bytesPerSam * $channels; $i++) 
    if (($bytesoffraw + $i) % ($bytesPerSam * $channels) === 0) { $bytesoff = $bytesoffraw + $i; break;} 

$ec = 0;

$bus = [];

$endat = 4;
$endatBytes = $bytesoff + ($bpsec * ($endat - $soffFromFileStart)) - 45;
kwas($l > $endatBytes, 'overflow 442');

$oi = 0;

if (0) {
$outt = substr($wsig, $bytesoff);
file_put_contents('/tmp/rout.wav', $outt); unset($outt);
}

$sdo = new stddev();

for($i=$bytesoff; $i < $endatBytes ; $i += (($channels * $bytesPerSam) * 1)) {

    kwas(($i % ($bytesPerSam * $channels)) === 0, 'bad mod');
    
    if (0) {
    echo($soffhun . ' ' . $i . "\n");
    exit(0);
    }
    
    $sec = ($i / $bpsec);
    $ti = intval(floor($sec * 10));
    
    for($j=0; $j < $channels; $j++) {
	$byte = $i + $j * $bytesPerSam;
	$subs = substr($wsig, $byte, $bytesPerSam);
	try {
	    kwas(strlen($subs) === $bytesPerSam, 'bad ppsam len');
	} catch(Exception $ex) { 
	    kwynn();
	}
	
	if ($useBytes === 1) $isigraw = ord($subs[0]);
	else {
	    $u = unpack($packf, $subs);
	    $isigraw = $u[1];
	}
	
	// if ($oi++ % 100 === 0) 
	    // echo $isigraw . "\n";

	$is10 = $isigraw;
	// $is10 = abs($isigraw);

	if ($j === 0) $j0 = $is10;
	if ($j === 1) $j1 = $is10;
	$sdo->put($is10);
   }
   
   if (!isset($bus[$ti])) $bus[$ti] = 0;
   
   if (!is_numeric($j0) || !is_numeric($j1)) {
       kwynn();
   }
   $bus[$ti] += $j0 + $j1;
}

var_dump($sdo->get());

// $dbc = pow(10, 17/10); // 17 decibel difference

$grtot = 0;

foreach($bus as $i => $v) {
    
    $grtot += $v;
    
    // echo($v) . "\n";
    
    $vl = log($v, 50);
    $vd = sprintf('%0.3f', $vl );
    
    // $cut = 5.50;
    
    // echo(/*($vl > $cut ? 1 : 0) . ' ' . sprintf('%0.2f', ($vl - $cut)) . ' ' . */ $vd . "\n");
    
}

echo(number_format($grtot) . "\n");



exit(0);
