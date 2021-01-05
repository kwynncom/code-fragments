<?php 

if (PHP_SAPI !== 'cli') exit(0); // in case this makes it to the web.  We do not want outside web users to be able to pound the time server

checkNTP();

function checkNTP() {

    global $argv;
    
    $server = 'time.nist.gov';
    
    if (isset($argv[1])) $server = $argv[1];
    
    echo('server: ' . $server . "\n");
    
$bit_max       = 4294967296;
$epoch_convert = 2208988800;

$header = '00';
$header .= sprintf('%03d',decbin(3)); // 3 indicates client
$header .= '011';
$request_packet = chr(bindec($header));

$socket = fsockopen('udp://'.$server, 123, $err_no, $err_str,1);
if (!$socket) die('cannot open connection');
for ($j=1; $j<40; $j++) $request_packet .= chr(0x0);

$local_sent_explode = explode(' ',microtime());
$local_sent = $local_sent_explode[1] + $local_sent_explode[0];

$originate_seconds = $local_sent_explode[1] + $epoch_convert;

$originate_fractional = round($local_sent_explode[0] * $bit_max);

$originate_fractional = sprintf('%010d',$originate_fractional);

$packed_seconds = pack('N', $originate_seconds);
$packed_fractional = pack("N", $originate_fractional);

$request_packet .= $packed_seconds;
$request_packet .= $packed_fractional;

if (!fwrite($socket, $request_packet)) die('bad socket write');
stream_set_timeout($socket, 1);

$sreslen = 48;
$response = fread($socket, $sreslen);
$local_received = microtime(true);
fclose($socket);

if (strlen($response) !== $sreslen) die('bad SNTP server result');

$unpack0 = unpack("N12", $response);

$remote_received_seconds = sprintf('%u', $unpack0[9])-$epoch_convert;
$remote_transmitted_seconds = sprintf('%u', $unpack0[11])-$epoch_convert;

$remote_received_fraction = sprintf('%u', $unpack0[10]) / $bit_max;
$remote_transmitted_fraction = sprintf('%u', $unpack0[12]) / $bit_max;

$remote_received = $remote_received_seconds + $remote_received_fraction;
$remote_transmitted = $remote_transmitted_seconds + $remote_transmitted_fraction;

$txt  = $local_sent . "\n";
$txt .= $remote_received . "\n";
$txt .= ($local_sent + $local_received) / 2 . "\n";
$txt .= $remote_transmitted . "\n";
$txt .= $local_received . "\n";

echo $txt;
}

// https://github.com/kwynncom/sntp-web-display/blob/master/utils/simple_standalone.php
