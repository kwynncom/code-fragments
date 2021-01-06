<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/../ticks/stddev.php');

$file = '/tmp/hwrset1.wav';

$bytesPerSam = 2;
$channels = 2;
$sampleRate = 8000;
$bitsPerSam = $bytesPerSam * 8;
$duration = 20;
$cmd = 'arecord -f S' . $bitsPerSam . '_LE -c ' . $channels . ' -r ' . $sampleRate . ' --device="hw:0,0" -d ' . $duration . ' > ' . $file;

// arecord -f S16_LE -c 2 -r 8000 --device="hw:0,0" -d 2 > /tmp/hwrset1.wav
if (0) {
kwas(unlink($file), 'delete failed');
exec($cmd);
}

$wsig = file_get_contents($file);
$l = strlen($wsig);
$bps = $channels * $bytesPerSam * $sampleRate;
$header = 44;

kwas($l === $bps * $duration + $header, 'bad length');

$wsig = substr($wsig, 44); // get rid of header
$l -= 44;

$min = $minl = PHP_INT_MAX;
$sto = false;

$soffhun = 4;
$sofbytes = $soffhun * intval(round(($bps / 100))) * $channels * $bytesPerSam;

$ec = 0;

for($i=($bps * 10) + $sofbytes; $i < $l - 8; $i += $channels * $bytesPerSam) {
    
    $sec = ($i / $bps);
    $ti = intval(floor($sec * 10));
    
    for($j=0; $j < 2; $j++) {
	$byte = $i + $j * 2;
	$subs = substr($wsig, $byte, 2);
	$u = unpack('s', $subs);
	$isigraw = $u[1];
	$is10 = $isigraw < 0 ? -$isigraw : $isigraw;
	// echo($j . ' ' . $is10 . "\n");
	if ($j === 0) $j0 = $is10;
	if ($j === 1) $j1 = $is10;
//	doStats($sto, $ti, $is10);
   }
   
   filterStats($sto, $ti, $j0, $j1);
   
    if (0) {echo(abs($j0 - $j1) . ' ' . $j1 . ' ' . $j0 . "\n");
    if ($ec++ > 8000) exit(0); }
    
    
}

function filterStats(&$stin, $i, $d0, $d1) {
    if ($d0 >= 30 && $d1 >= 30) $d = 1;
    else $d = 0;
    
    doStats($stin, $i, $d);
    
}

foreach($sto as $i => $o) {
    
    $a = $o->get();
    
    // var_dump($a); continue;
    $s  = '';    
    $av = $a['a'];
    
    if ($av > 0.25) $s .= 1;
    else $s .= 0;
    
    $ad = sprintf('%0.5f', $av);
    // $ad = number_format(round($av));

    // $s .= $i . ' ';
    // $s .= $ad;
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
   
    // if ($din < 0.001) return;     // for filtering weird logs //  
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