<?php

require_once('/opt/kwynn/kwutils.php');

$file = '/tmp/ant.wav';

$bytesPerSam = 4;
$channels = 2;
$sampleRate = 48000;
$bitsPerSam = $bytesPerSam * 8;
$duration = 90;

$useBytes = $bytesPerSam;

if      ($bytesPerSam === 4) $packf = 'l';
else if ($bytesPerSam === 2) $packf = 'S';

$cmd = 'arecord -f S' . $bitsPerSam . '_LE -c ' . $channels . ' -r ' . $sampleRate . ' --device="hw:0,0" -d ' . $duration . ' > ' . $file;

if (0) {
kwas(unlink($file), 'delete failed');
exec($cmd);
exit(0);
}

$wsig = file_get_contents($file);
$l = strlen($wsig);
$bpsec = $channels * $bytesPerSam * $sampleRate;
$header = 44;

kwas($l === $bpsec * $duration + $header, 'bad length');

$wsig = substr($wsig, 44); // get rid of header
$l -= 44;

$minv = PHP_INT_MAX;

$soffFromFileStart = 70;
$endat = 75;


$soffhun = 5;
$spp60kHz = 0.000016667;
$peroff = 0.4;
$secsoff = $spp60kHz * $peroff + $soffhun / 100 + $soffFromFileStart;
$bytesoffraw = intval(round($bpsec * $secsoff));

for($i=0; $i < $bytesPerSam * $channels; $i++) 
    if (($bytesoffraw + $i) % ($bytesPerSam * $channels) === 0) { $bytesoff = $bytesoffraw + $i; break;} 

$ec = 0;

$bus = [];


$endatBytes = $bytesoff + ($bpsec * ($endat - $soffFromFileStart)) - 45;
kwas($l > $endatBytes, 'overflow 442');

$oi = 0;

if (0) {
$outt = substr($wsig, $bytesoff);
file_put_contents('/tmp/rout.wav', $outt); unset($outt);
}

for($li=0; $li < 2; $li++) {
for($i=$bytesoff; $i < $endatBytes ; $i += (($channels * $bytesPerSam) * 1)) {
    kwas(($i % ($bytesPerSam * $channels)) === 0, 'bad mod');
    
    $sec = ($i / $bpsec);
    $ti = intval(floor($sec * 10));
    
    for($j=0; $j < $channels; $j++) {
	$byte = $i + $j * $bytesPerSam;
	$subs = substr($wsig, $byte, $bytesPerSam);
        kwas(strlen($subs) === $bytesPerSam, 'bad ppsam len');

        $u = unpack($packf, $subs);
        $isigraw = $u[1];

	$is10 = $isigraw;

	if ($is10 < $minv) $minv = $is10;
	
	if ($li === 0) continue 2;

	if ($j === 0) $j0 = $is10 - $minv;
	if ($j === 1) $j1 = $is10 - $minv;

   }
   
   if (!isset($bus[$ti])) {
       $bus[$ti]['v'] = 0;
       $bus[$ti]['n'] = 0;
   }
   $bus[$ti]['v'] += $j0 + $j1;
   $bus[$ti]['n'] += 1;
}
}

$minav = PHP_INT_MAX;

foreach($bus as $i => $a) {
    
    $v10 = ($a['v'] / $a['n']);
    $v20 = intval(round($v10));
    if ($v20 < $minav) $minav = $v20;
    $bus[$i]['v20'] = $v20;
    

    
}

foreach($bus as $i => $a) {
    $v30 = $a['v20'] - $minav;
    $v40 = number_format($v30);
    $v50 = sprintf('%11s', $v40);
    
    echo($v50) . "\n";
    
}

echo('min = ' . number_format($minv) . "\n");

exit(0);
