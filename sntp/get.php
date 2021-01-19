<?php

require_once('dao.php');
require_once('sntp.php');

class ntpQuotaGet {
    
    const resetUntil = '2021-01-18 21:46';
    const defaultMinPoll = 67;
    const defaultPri = 50;
    const maxTries = 5;

    public function get($nreq = 1) { 
	$res = [];
	$i = 0;
	$iok = 0;
	do {
	    $s = $this->dao->get();
	    $this->geto->setServer($s);
	    $dat = $this->geto->pget();
	    $this->dao->put($dat);
	    if (isset($dat['OK'])) { $t = ['off' => $dat['calcs']['coffset'], 'srv' => $s]; $iok++; }
	    else		     $t = ['status' => $dat['status'], 'server' => $dat['server']];
	    $res[] = $t;
	    kwynn();
	} while($iok < $nreq && $i < self::maxTries + $nreq);
	
	return $res;
	
    }
    
    public function __construct() {
	$this->geto = new sntp_get_actual();
	$this->dao = new dao_ntp_pool_quota(time() < strtotime(self::resetUntil) ? self::getAllServers() : null);
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
	    'hosts' => ['kwynn.com'],
	    'minpoll' => -1,
	    'pri' => 70
	    ];
	
	return $a;
    }
}
