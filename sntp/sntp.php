<?php

require_once('/opt/kwynn/kwutils.php');

class sntp_sa {
    
const bit_max       = 4294967296;
const epoch_convert = 2208988800;
const server = 'kwynn.com';
const billion = 1000000000;
const expectedReceiptLen = 48;

public static function get() {
    $r = self::getall();
    return $r;
}

private static function getall() {
    $ps   = self::getPacketAndSocket();
    $cres = self::get10($ps); unset($ps); // crit as in the critical, time-sensitive processing -- the NTP call itself for one.
    $pres = self::parseNTPResponse($cres['r']); unset($cres['r']);
    $ma = array_merge($cres, $pres);
    $sres = self::sharpen($ma);
    $calca = self::calcs($sres);
    return ['calcs' => $calca, 'parsed' => $pres, 'based' => $sres, 'local' => $cres];
}

private static function getPacketAndSocket() {
    
    $header = '00';
    $header .= sprintf('%03d',decbin(3)); // 3 indicates client
    $header .= '011';
    $request_packet = chr(bindec($header)); unset($header);
    for ($j=1; $j < 40; $j++) $request_packet .= chr(0x0);
    set_error_handler('kw_error_handler', E_ALL - E_WARNING);
    $socket = @fsockopen('udp://'. self::server, 123, $err_no, $err_str); 
    kwas($socket, 'cannot open connection to ' . self::server);
    set_error_handler('kw_error_handler', E_ALL);
    return ['pack' => $request_packet, 'sock' => $socket];
}
    
private static function get10($packsock) {
    $socket = $packsock['sock'];
    $request_packet = $packsock['pack'];
    stream_set_timeout($socket, 1);
    
    $lsta = nanopk(NANOPK_U | NANOPK_UNSOF);
    $originate_seconds = $lsta['U'] + self::epoch_convert;
    $originate_fractional = intval(round($lsta['Unsof'] * self::bit_max));
    $originate_fractional = sprintf('%010d',$originate_fractional);
    $packed_seconds = pack('N', $originate_seconds);
    $packed_fractional = pack("N", $originate_fractional);
    $request_packet .= $packed_seconds;
    $request_packet .= $packed_fractional;
    return self::get20($socket, $request_packet);

}

private static function get20($sock, $rqpack) {
    $b = nanotime_array();
    if (!fwrite($sock, $rqpack)) throw new Exception ('bad socket write');
    $response = fread($sock, self::expectedReceiptLen);
    $e = nanotime_array();
    fclose($sock);
    kwas(strlen($response) === self::expectedReceiptLen, 'bad SNTP receipt length');
    return ['r' => $response, 'b' => $b, 'e' => $e];
    
}

private static function parseNTPResponse($response) {

    $unpack0 = unpack("N12", $response);
    
    $r['rrs'] = sprintf('%u', $unpack0[ 9]) - self::epoch_convert; // remote receive packet second-precision ts
    $r['rrf'] = sprintf('%u', $unpack0[10]) / self::bit_max;       // remote receive packet fractional time
    $r['rss'] = sprintf('%u', $unpack0[11]) - self::epoch_convert; // remote sent ...
    $r['rsf'] = sprintf('%u', $unpack0[12]) / self::bit_max;       // ...
    $stratum  = self::getStratum($response); kwas($stratum && intval($stratum) >= 1, 'SNTP Kiss of Death (KOD)');
    $r['stratum'] = $stratum;
    return $r;
}

private static function getStratum($response) {
    $unpack1 = unpack("C12", $response);
    $stratum_response =  base_convert($unpack1[2], 10, 2);
    $stratum_response = bindec($stratum_response);
    return $stratum_response;
}

private static function sharpen($n) {
    
    $ia = [$n['rrs'], $n['rss'], $n['b']['s'], $n['e']['s']];
    
    $min = min($ia); self::veryRecentTSOrDie($min);
    
    $ra['base'] = $min;
    $ra['ls'  ] = $n['b']['s']  - $min + $n['b']['ns'] / self::billion;
    $ra['lr'  ] = $n['e']['s']  - $min + $n['e']['ns'] / self::billion;
    $ra['rr'  ] = ($n['rrs'] - $min) + $n['rrf']; // remote received
    $ra['rs'  ] = ($n['rss'] - $min) + $n['rsf']; // remote sent
    $ra['stratum'] = $n['stratum'];
    
    return $ra;
}

public static function mtstoa($sin) { // microtime-stamp to array
    $a1 = explode(' ', $sin);
    $i  = intval($a1[1]); self::veryRecentTSOrDie($i);
    $f  = floatval($a1[0]);
    $ar[0] = $i;
    $ar[1] = $f;
    $ar[2] = $i + $f;
    return $ar;
}

public static function veryRecentTSOrDie($iin) {
    static $now = false;
    if ($now === false) $now = time();
    kwas(!(abs($iin - $now) > 20 && isAWS()), 'timestamps too far off');
}

private static function calcs($r) {
    $re['coffset'] = -((($r['rr'] - $r['ls']) + ($r['rs'] - $r['lr'])) / 2); // I am using opposite sign of official
    $re['srvd'   ] = $r['rs'] - $r['rr'];
    $re['outd'   ] = $r['rr'] - $r['ls'];
    $re['ind'    ] = $r['lr'] - $r['rs'];
    
    return $re;
}
}

if (didCLICallMe(__FILE__)) sntp_sa::get();