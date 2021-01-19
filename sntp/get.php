<?php

require_once('dao.php');

class ntpGet {
    
    const reset = false;

    public function get() {
	
    }
    
    public function __construct() {
	$this->dao = new dao_ntp_pool_quota(self::reset ? self::getAllServers() : null);
    }
    
    public static function getI() {
	
	
    }
   
    private static function getAllServers() {
	
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
	
	return $a;
    }
}
