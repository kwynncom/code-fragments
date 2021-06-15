<?php

require_once('/opt/kwynn/kwutils.php');
require_once('get.php'); // needed for standalone to get packet

class sntp_get_actual extends ntpQuotaGet {
    
const bit_max		 = 4294967296;
const epoch_convert	 = 2208988800;

protected function __construct() { 
    $this->server = $this->sock = false;
    $this->basep = $this->getBasePacket();
}

protected function pget() { return $this->getall($this->basep); }

private function retErr($msg) {
    return [
	'status' => $msg,
	'server' => $this->server,
	'ts'   => microtime(1),
	
	];
}

private function getall($basep) {
    $fullp  = self::getFullPacket($basep) ; unset($basep);
    $rawres = $this->getTime($fullp);
    if (!is_array($rawres)) return $this->retErr($rawres);
    $res = self::getCalcs($rawres, $this->server); 
    if (!is_array($res)) $this->retErr($res);
    return $res;
}

private static function getCalcs($rawres, $server = '') {
    if (!isset($rawres['r'])) {
	$t = $rawres;
	$rawres = [];
	$rawres['r'] = $t; unset($t);
    }
    $parsedRes = self::parseNTPResponse($rawres['r']);
    if (!isset($rawres['b'])) return $parsedRes;
    if (count($rawres) > 1) unset($rawres['r']);
    if (!is_array($parsedRes)) return $parsedRes;
    $ma = array_merge($rawres, $parsedRes);
    $sres = self::sharpen($ma);
    $calca = self::calcs($sres);
    return ['calcs' => $calca, 'parsed' => $parsedRes, 'based' => $sres, 'local' => $rawres, 
	'server' => $server, 'OK' => true, 'status' => 'OK', 'ts' => microtime(1)];   
}

public static function parsePacket($rawres) {
    $p = self::parseNTPResponse($rawres);  
    $fs = ['recv' => ['rrs', 'rrf'], 'send' => ['rss', 'rsf']];
    $r20 = [];
    foreach($fs as $name => $vs) 
	$r20[$name] = $p[$vs[0]] * M_BILLION + intval(round(($p[$vs[1]] * M_BILLION)));
    
    $res = array_merge($p, $r20);
    $res['avg'] = ($res['recv'] + $res['send']) >> 1;
    $res['internalTime'] = $res['send'] - $res['recv'];
    return $res;
    
    
}

public static function getClientPacket() { return self::getFullPacket(self::getBasePacket()); } 

private static function getBasePacket() {
    //      no warn / n/a             SNTP  v      3 = client
    $header  = '00' . sprintf('%03d',decbin(4)) . '011';
    // we now have 8 bits    00100011 or decimal 35 or ASCII # character
    $request_packet = chr(bindec($header)); unset($header);
    // now in raw byte form
    
    for ($j=1; $j <= 39; $j++) $request_packet .= chr(0x0); // 39 bytes of raw 0 after #, or 40 bytes so far
    return $request_packet;
}

protected function setServer($sin) {
    // kwas($sin, 'should not be false - setServer - 1216');
    if ($this->server === $sin) return;
    $this->server = $sin;
    $this->setSocket();
    
}

private function setSocket() {
    
    if (isset(		$this->asocks[$this->server])) {
	$this->socket = $this->asocks[$this->server];
	return;
    }
    
    set_error_handler('kw_error_handler', E_ALL - E_WARNING);
    $socket = fsockopen('udp://'. $this->server, 123, $err_no, $err_str); 
    kwas($socket, 'cannot open connection to ' . $this->server);
    set_error_handler('kw_error_handler', E_ALL);
    stream_set_timeout($socket, 1);
    $this->socket = $this->asocks[$this->server] = $socket;
}

public function __destruct() {  
    if (!isset($this->asocks)) return;
    foreach($this->asocks as $s) fclose($s);
}

private static function getFullPacket($base) {
    $lsta = nanopk(NANOPK_U | NANOPK_UNSOF); // integer seconds and float fraction of seconds
    $originate_seconds = $lsta['U'] + self::epoch_convert; // whole / integer UNIX Epoch seconds converted to NTP Epoch
    $originate_fractional = intval(round($lsta['Unsof'] * self::bit_max)); // 0.84830 fractional seconds as a fraction of binary 1111...111 for 32 unsigned bits
    $originate_fractional = sprintf('%010d',$originate_fractional); // make sure precisely 10 zero filled decimal digits
    $packed_seconds    = pack('N', $originate_seconds   );     // N = unsigned long (always 32 bit, big endian byte order) - turn into 4 raw bytes
    $packed_fractional = pack('N', $originate_fractional);
    $request_packet  = $base; unset($base);
    $request_packet .= $packed_seconds;
    $request_packet .= $packed_fractional;
    return $request_packet; // we now have 48 bytes with the final 8 bytes representing the outgoing local timestamp
}

private function getTime($rqpack) {
    static $expectedReceiptLen = 48;
    $b = nanotime_array();
    if (!fwrite($this->socket, $rqpack)) return 'bad socket write';
    $response = fread($this->socket, $expectedReceiptLen);
    $e = nanotime_array();
    $len = strlen($response);

    if ($len !== $expectedReceiptLen) return 'bad len';
    return ['r' => $response, 'b' => $b, 'e' => $e];
}

private static function parseNTPResponse($response) {

    $stratum  = self::getStratum($response); 
    if (!($stratum && intval($stratum) >= 1)) return 'SNTP Kiss of Death (KOD)';

    $unpack0 = unpack("N12", $response);
    $r['rrs'] = sprintf('%u', $unpack0[ 9]) - self::epoch_convert; // remote receive packet second-precision ts
    $r['rrf'] = sprintf('%u', $unpack0[10]) / self::bit_max;       // remote receive packet fractional time
    $r['rss'] = sprintf('%u', $unpack0[11]) - self::epoch_convert; // remote sent ...
    $r['rsf'] = sprintf('%u', $unpack0[12]) / self::bit_max;       // ...
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
