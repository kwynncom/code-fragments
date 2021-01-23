<?php

require_once('dao.php');
require_once('sntp.php');
require_once('/opt/kwynn/kwcod.php');

class ntpQuotaGet {
    
    const resetUntil = '2021-01-22 23:00';
    const defaultMinPoll = 67;
    const defaultPri = 50;
    const maxFails = 5;
    const defaultGets = 3;

    public static function get() {
	$o = new self();
	$o->getI();
    }
    
    public function getI() { 
	$nreq = $this->argN;
	$res = [];
	$ino = $iok = 0;
	do {
	    $s = $this->dao->get($this->argN > self::defaultGets, $this->ip4, $this->ip6);
	    $this->geto->setServer($s);
	    $dat = $this->geto->pget();
	    $this->dao->put($dat);
	    if (isset($dat['OK'])) { $t = ['off' => $dat['calcs']['coffset'], 'srv' => $s, 'all' => $dat];	    $iok++; }
	    else		   { $t = ['status' => $dat['status'], 'server' => $dat['server']]; $ino++; }
	    $res[] = $t;
	    $this->out($t);
	    kwynn();
	} while($iok < $nreq && $ino < self::maxFails);
	
	$this->outstats();
	
	$this->badout();
	return $res;
	
    }
    
    private function outstats() {
	if ($this->argN <= self::defaultGets) return;
	var_dump($this->sdo->get());
    }
    
    private function badout() {
	if (!isset($this->badrd)) return;
	foreach($this->badrd as $s) echo($s);
    }
    
    private function out($ddin) {
	
	if (isset($ddin['off'])) {
	    $v =  $ddin['off'];
	    $v *= 1000;
	    $this->sdo->put($v);
	    $vd = sprintf('%+06.2f', $v);
	    $nms = self::outnet($ddin['all']);
	    $nmsd = sprintf('%03d', $nms);
	    $s = ($vd . ' ' . $nmsd  . ' ' . $ddin['srv'] .   "\n");
	    echo($s);
	} else {
	    $s = ($ddin['status'] . ' ' . $ddin['server'] . "\n");
	    $this->badrd[] = $s;
	}	
    }
    
    private static function outnet($d) {
	$ns = self::nanoatoi($d['local']['e']) - self::nanoatoi($d['local']['b']);
	$ms = intval(round($ns / M_MILLION));
	return $ms;
    }
    
    public static function nanoatoi($ain) {
	return $ain['s'] * M_BILLION + $ain['ns'];

    }
    
    public function __construct() {
	$this->sdo = new stddev();
	$this->geto = new sntp_get_actual();
	$this->dao = new dao_ntp_pool_quota(time() < strtotime(self::resetUntil) ? self::getAllServers() : null);
	$this->setArgs();
    }
    
    private function setArgs() {
	global $argv;
	
	$this->ip4 = false;
	$this->ip6 = false;
	
	foreach($argv as $a) 
	    if (is_numeric($a) && $a > 0) $this->argN = $a;
	    else if ($a === '-4') $this->ip4 = true;
	    else if ($a === '-6') $this->ip6 = true;

	if (!isset($this->argN))
		   $this->argN = self::defaultGets;
	
    }
   
    private static function getAllServers() {
	$as = self::get10();
	$ps = $hs = [];
	foreach($as as $k => $a) {
	    $th = $tp = [];
	    if (isset( $a['hosts'])) 
		 $th = $a['hosts'];
	    else $th = $a;
	    
	    if (!isset($a['minpoll'])) $tp['minpoll'] = self::defaultMinPoll;
	    else		       $tp['minpoll'] = $a['minpoll'];
	    
	    if (isset($a['pri'])) $tp['pri'] = $a['pri'];
	    else		  $tp['pri'] = self::defaultPri;
	    
	    $tp['_id'] = $k;
	    $ps[$k] = $tp;

	    foreach($th as $thr) {
		$id = $k . '-' . $thr;
		$hs[] = [ '_id'  => $id,
		          'pool' => $k,
			  'server' => $thr
		    ];
	    }
	}
	
	return ['pools' => $ps, 'servers' => $hs];
    }
    
    private static function get10() {
	
	$nistHosts = [
	    '129.6.15.26',
	    '129.6.15.27',
	    '129.6.15.28',
	    '129.6.15.29',
	    '129.6.15.30',
	    '[2610:20:6f15:15::26]',
	    '[2610:20:6f15:15::27]'	  
	];	
	
	$a['NIST'] = [ 'minpoll' => 4, 'hosts' => $nistHosts	]; unset($nistHosts);

	$a['USG'] = [ 'hosts' => ['rolex.usg.edu']	];
	
	$a['VATech'] = [ 
	    'hosts' => [
		'ntp-1.vt.edu',
		'ntp-2.vt.edu',
		'ntp-3.vt.edu',
		'ntp-4.vt.edu'
	    ],
	];
	
	$a['ub'] = ['ntp.ubuntu.com'];
	$a['u0'] = ['0.ubuntu.pool.ntp.org'];	
	$a['u1'] = ['1.ubuntu.pool.ntp.org'];
	$a['u2'] = ['2.ubuntu.pool.ntp.org'];
	$a['kwynn'] = [
	    'hosts' => ['kwynn.com', '34.193.238.16', '[2600:1f18:23ab:9500:acc1:69c5:2674:8c03]'],
	    'minpoll' => -1,
	    'pri' => 70
	    ];
	
	return $a;
    }
}

if (didCLICallMe(__FILE__)) ntpQuotaGet::get();
