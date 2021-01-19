<?php

require_once('/opt/kwynn/kwutils.php');

class sntp_get_actual extends ntpQuotaGet {
    
const bit_max		 = 4294967296;
const epoch_convert	 = 2208988800;

protected function __construct() { 
    $this->server = $this->sock = false;
    $this->basep = $this->getBasePacket();
}

protected function getOffset() {
    $r = self::getall($this->basep, $this->socket);
    if (!$r) return $r;
    return $r['calcs']['coffset'];
}

protected function pget() { return $this->getall($this->basep); }

private function getall($basep) {
    $fullp  = self::getFullPacket($basep) ; unset($basep);
    $rawres = $this->getTime($fullp);
    if (!$rawres) return false;
    
    $parsedRes = self::parseNTPResponse($rawres['r']); unset($rawres['r']);
    $ma = array_merge($rawres, $parsedRes);
    $sres = self::sharpen($ma);
    $calca = self::calcs($sres);
    return ['calcs' => $calca, 'parsed' => $parsedRes, 'based' => $sres, 'local' => $rawres];
}

private static function getBasePacket() {
    $header  = '00' . sprintf('%03d',decbin(3)) . '011';
    $request_packet = chr(bindec($header)); unset($header);
    for ($j=1; $j < 40; $j++) $request_packet .= chr(0x0);
    return $request_packet;
}

protected function setServer($sin) {
    if ($this->server === $sin) return;
    $this->server = $sin;
    $this->setSocket();
    
}

private function setSocket() {
    set_error_handler('kw_error_handler', E_ALL - E_WARNING);
    $socket = @fsockopen('udp://'. $this->server, 123, $err_no, $err_str); 
    kwas($socket, 'cannot open connection to ' . $this->server);
    set_error_handler('kw_error_handler', E_ALL);
    stream_set_timeout($socket, 1);
    $this->socket = $socket;
}

public function __destruct() {  
    if ($this->socket) fclose($this->socket); 
}

private static function getFullPacket($base) {
    $lsta = nanopk(NANOPK_U | NANOPK_UNSOF);
    $originate_seconds = $lsta['U'] + self::epoch_convert;
    $originate_fractional = intval(round($lsta['Unsof'] * self::bit_max));
    $originate_fractional = sprintf('%010d',$originate_fractional);
    $packed_seconds    = pack('N', $originate_seconds   );
    $packed_fractional = pack('N', $originate_fractional);
    $request_packet  = $base; unset($base);
    $request_packet .= $packed_seconds;
    $request_packet .= $packed_fractional;
    return $request_packet;
}

private function getTime($rqpack) {
    static $expectedReceiptLen = 48;
    $b = nanotime_array();
    if (!fwrite($this->socket, $rqpack)) throw new Exception ('bad socket write');
    $response = fread($this->socket, $expectedReceiptLen);
    $e = nanotime_array();

    if (strlen($response) !== $expectedReceiptLen) return false;
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
    
    $min = min([$n['rrs'], $n['rss'], $n['b']['s'], $n['e']['s']]); 
    
    $ra = [];
    $ra['base'] = $min;
    $ra['ls'  ] = $n['b']['s'] - $min  + $n['b']['ns'] / M_BILLION;
    $ra['lr'  ] = $n['e']['s'] - $min  + $n['e']['ns'] / M_BILLION;
    $ra['rr'  ] = ($n['rrs']   - $min) + $n['rrf']; // remote received
    $ra['rs'  ] = ($n['rss']   - $min) + $n['rsf']; // remote sent
    
    return $ra;
}

private static function calcs($r) {
    $re = [];
    $re['coffset'] = -((($r['rr'] - $r['ls']) + ($r['rs'] - $r['lr'])) / 2); // I am using opposite sign of official
    $re['srvd'   ] = $r['rs'] - $r['rr'];
    $re['outd'   ] = $r['rr'] - $r['ls'];
    $re['ind'    ] = $r['lr'] - $r['rs'];
    return $re;
}
}
