<?php

require_once('dao.php');
require_once('sntp.php');
require_once('/opt/kwynn/kwcod.php');
require_once('out.php');
require_once('config_servers.php');

class ntpQuotaGet {

    const maxFails = 5;
    const defaultGets = 3;
    const iServersDefaultGet = 5; // internal (Kwynn) servers default get
    
    private $xsrvs = false;
    private $loc   = false;

    public static function get() {
	$o = new self();
	$o->getI();
    }
    
    public function getI() { 
	$nreq = $this->argN;
	$res = [];
	$ino = $iok = 0;
	do {
	    $si = $this->dao->get($this->argN > self::defaultGets, $this->ip4, $this->ip6, $this->xsrvs, $this->loc);
	    if (!$si) exit(0);
	    $this->geto->setServer($si['server']);
	    $dat = $this->geto->pget();
	    $this->dao->put($dat);
	    if (isset($dat['OK'])) { $t = ['off' => $dat['calcs']['coffset'], 'si' => $si, 'all' => $dat];	    $iok++; }
	    else		   { $t = ['status' => $dat['status'], 'server' => $dat['server']]; $ino++; }
	    $res[] = $t;
	    $this->out->out($t);
	    kwynn();
	} while($iok < $nreq && $ino < self::maxFails);
	
	$this->out->outFinal();
	
	$this->out->badout();
	return $res;
    }

    public function __construct() {
	
	$this->geto = new sntp_get_actual();
	$this->dao = new dao_ntp_pool_quota(time() < strtotime(ntp_servers::resetUntil) ? ntp_servers::get() : null);
	$this->setArgs();
	$this->out = new ntp_output($this->argN > self::defaultGets);
    }
    
    private function setArgs() {
	global $argv;
	
	$this->ip4 = false;
	$this->ip6 = false;
	
	foreach($argv as $a) 
	    if (is_numeric($a) && $a > 0) $this->argN = $a;
	    else if ($a === '-4') $this->ip4 = true;
	    else if ($a === '-6') $this->ip6 = true;
	    else if ($a === 'o' || $a === '-o') $this->xsrvs = true;
	    else if ($a === 'l' || $a === '-l') $this->loc = true;

	if (!isset($this->argN)) 
	    if ($this->xsrvs) $this->argN = self::defaultGets;
	    else	      $this->argN = self::iServersDefaultGet;
	
    }
}

if (didCLICallMe(__FILE__)) ntpQuotaGet::get();
