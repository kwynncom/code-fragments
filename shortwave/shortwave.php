<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/../ticks/batch1/stddev.php');

$file = '/tmp/r1.wav';

$bytesPerSam = 4;
$channels = 2;
$sampleRate = 48000;
$bitsPerSam = $bytesPerSam * 8;
$duration = 90;

if      ($bytesPerSam === 4) $packf = 'l';
else if ($bytesPerSam === 2) $packf = 'S';

$cmd = 'arecord -f S' . $bitsPerSam . '_LE -c ' . $channels . ' -r ' . $sampleRate . ' --device="hw:0,0" -d ' . $duration . ' > ' . $file;

// arecord -f S16_LE -c 2 -r 8000 --device="hw:0,0" -d 2 > /tmp/hwrset1.wav
if (0) {
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

$soffhun = 5;
$spp60kHz = 0.000016667;
$peroff = 0.1;
$sofbytesraw = intval(round(($spp60kHz * $peroff + ($soffhun * ($bpsec / 100))) * $channels * $bytesPerSam));

for($i=0; $i < $bytesPerSam; $i++) 
    if (($sofbytesraw + $i) % $bytesPerSam === 0) { $sofbytes = $sofbytesraw + $i; break;} 

$ec = 0;

$bus = [];

for($i=($bpsec * 30) + $sofbytes; $i < $l - 8; $i += (($channels * $bytesPerSam) * 5)) {
    
    $sec = ($i / $bpsec);
    $ti = intval(floor($sec * 10));
    
    for($j=0; $j < $channels; $j++) {
	$byte = $i + $j * $bytesPerSam;
	$subs = substr($wsig, $byte, $bytesPerSam);
	$u = unpack($packf, $subs);
	$isigraw = $u[1];
	$is10 = $isigraw < 0 ? -$isigraw : $isigraw;
	// echo($j . ' ' . $is10 . "\n");
	if ($j === 0) $j0 = $is10;
	if ($j === 1) $j1 = $is10;
	// doStats($sto, $ti, $is10);
   }
   
   if (!isset($bus[$ti])) $bus[$ti] = 0;
   $bus[$ti] += $j0 + $j1;
   
    
}

$sdo = new stddev();

foreach($bus as $i => $v) $sdo->put($v);
$sdr = $sdo->get();
$av = $sdr['a'];


foreach($bus as $i => $v) {
    
    $d = $v - $av;
    
    if ($d > 0) echo 1;
    else	echo 0;
    echo ' ';
    echo(sprintf('%010d', $d));
    echo "\n";
    
    // echo(number_format($v - $av) . "\n");
    
}

exit(0);
