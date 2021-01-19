<?php

require_once('dao.php');

class ntpGet {
    
    const reset = true;
    const defaultMinPoll = 67;

    public function get() { return $this->dao->get();    }
    
    public function __construct() {
	$this->dao = new dao_ntp_pool_quota(self::reset ? self::getAllServers() : null);
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
	    'minpoll' => -1
	    ];
	
	return $a;
    }
}
