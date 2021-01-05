<?php

require_once('/opt/kwynn/kwutils.php');

$sig = file_get_contents('/tmp/hwrset1.wav');
$l = strlen($sig);
kwas($l === 3840044, 'bad length');

for($i=44 + 8 * 48000 * 2; $i < $l - 4; $i += 40) {
    $u = unpack('V', $sig, $i); // I am unpacking as signed versus unsigned
    $sec = ($i / 384000);
    $sd  = sprintf('%0.2f', $sec);
    echo($sd . ' ' . $u[1] . "\n");    
    
    
}


exit(0);


 
// $ arecord -f S32_LE -c 2 -r 48000 --device="hw:0,0" -d 10 > /tmp/hwrset1.wav
// Recording WAVE 'stdin' : Signed 32 bit Little Endian, Rate 48000 Hz, Stereo
// 3,840,044 bytes
// 44 bytes wav header
// 4 bytes per sample X 2 channels X 48ksamples/s X 10s = 3,840,044
// 8 bytes per sample X 48k samples

// pack / unpack:  V	unsigned long (always 32 bit, little endian byte order)

// 384,000 bytes / s
