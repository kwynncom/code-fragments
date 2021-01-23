<?php

require_once('dao.php');
require_once('sntp.php');
require_once('/opt/kwynn/kwcod.php');
require_once('out.php');
require_once('config_servers.php');

class ntpQuotaGet {

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
	    $this->out->out($t);
	    kwynn();
	} while($iok < $nreq && $ino < self::maxFails);
	
	$this->out->outstats($this->argN > self::defaultGets);
	
	$this->out->badout();
	return $res;
    }

    public function __construct() {
	$this->out = new ntp_output();
	$this->geto = new sntp_get_actual();
	$this->dao = new dao_ntp_pool_quota(time() < strtotime(ntp_servers::resetUntil) ? ntp_servers::get() : null);
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
}

if (didCLICallMe(__FILE__)) ntpQuotaGet::get();
