<?php

require_once('/opt/kwynn/kwutils.php');

$wsig = file_get_contents('/tmp/hwrset1.wav');
$l = strlen($wsig);
kwas($l === 3840044, 'bad length');

$wsig = substr($wsig, 44); // head rid of header
$l -= 44;

for($i= 8 * 48000 * 9; $i < $l - 8; $i += 20) {
    for($j=0; $j < 2; $j++) {
	$byte = $i + $j * 4;
	$subs = substr($wsig, $byte, 4);
	$u = unpack('l', $subs); // I am unpacking as signed versus unsigned
	$isigraw = $u[1];
	$isd = $isigraw;
	
	// $iss = procSig($isigraw); // instantaneous signed sig
	// $isig = round(log(4294967296 - $isigraw));
	// $isig = number_format(4294967296 - $isigraw);
	// $isig = log($isigraw);
	// $isd  = sprintf('%013s', $iss);
	// $isd  = sprintf('%02.1f', $isig);
	$sec = ($i / 384000);
	$deg = intval(round((21600000 / $sec))) % 360;

	$sd  = sprintf('%0.2f', $sec);
	$dd  = sprintf('%03d' , $deg);
	$bd  = sprintf('%07d', $byte);
	echo($bd . ' ' . $j . ' ' . $dd . ' ' . $sd . ' ' . $isd . "\n");    
    }
}

exit(0);

function procSig($sin) {
    static $ck  = 1 << 31;
    
    if ($ck & $sin) return -(0xffff ^ $sin) + 1;
}

 
// $ arecord -f S32_LE -c 2 -r 48000 --device="hw:0,0" -d 10 > /tmp/hwrset1.wav
// Recording WAVE 'stdin' : Signed 32 bit Little Endian, Rate 48000 Hz, Stereo
// 3,840,044 bytes
// 44 bytes wav header
// 4 bytes per sample X 2 channels X 48ksamples/s X 10s = 3,840,044
// 8 bytes per sample X 48k samples

// pack / unpack:  V	unsigned long (always 32 bit, little endian byte order)

// 384,000 bytes / s
