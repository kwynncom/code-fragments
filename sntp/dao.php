<?php

require_once('/opt/kwynn/kwutils.php');
require_once('/opt/kwynn/mongodb2.php');

class dao_ntp_pool_quota extends dao_generic_2 {
    
    const dbName = 'sntp2';
    
    public function __construct($sin) {
	parent::__construct(self::dbName, __FILE__);
	$this->colls = ['s' => 'servers', 'p' => 'pools', 'r' => 'result'];
	$this->creTabs($this->colls);
	$this->init($sin);
    }
    
    public function put($dat) {
	$dat['_id'] = $this->rcoll->getSeq2('idoas');
	$this->rcoll->insertOne($dat);
    }
    
    private function init($srvs) {
	
	static $now = false;
	
	if (!$now) $now = time();
	
	if (!$srvs) return;
	$cs = $this->colls;
	unset($cs['u']);

	foreach($cs as $c => $n) {
	    $tc = $c . 'coll';
	    $this->$tc->drop();
	    foreach($srvs[$n] as $r) {
		if ($n === 'servers') $minpoll = $srvs['pools'][$r['pool']]['minpoll'];
		else		      $minpoll = $r['minpoll'];
		
		$r['lts'] = $now - $minpoll - 1;
		$r['minpoll'] = $minpoll;
		$this->$tc->insertOne($r);
	    }
	}
    }
    
    
    public function get($hfo, $ip4, $ip6) { // $hfo === high-frequency polls only
	$now = microtime(1);
	$hu  = date('r', $now);
	
	if (!$hfo)  $q20 = ['$expr' => ['$lt' => ['$lts', ['$subtract' => [$now, '$minpoll']]]]];
	else	    $q20 = ['minpoll' => ['$lte' => 0.001]];
	
	$q4 = false;
	if	($ip4) $q4 = ['server' => new MongoDB\BSON\Regex('^(\d{1,3}\.?){4}$')];
	else if ($ip6) $q4 = ['server' => new MongoDB\BSON\Regex('^\[[a-fA-F0-9:]+\]$'  )];
	
	if (!$q4) $q10 = $q20;
	else      $q10 = ['$and' => [$q20, $q4]];
	
	$sorta = ['sort' => ['lts' => 1, 'pri' => -1]];

	$upv = ['lts' => $now, 'hu' => $hu];	
	
	if (!$ip4 && !$ip6) {
	    $p = $this->pcoll->findOne($q20, $sorta ); kwas($p, 'no server within quota');
	    $this->pcoll->upsert(['_id' => $p['_id']], $upv );
	    $h = $this->scoll->findOne(['pool' => $p['_id']], $sorta); kwas($h, 'no server within quota');
	}
	else $h = $this->scoll->findOne($q10, $sorta); kwas($h, 'no server within quota');
	$this->scoll->upsert(['_id' => $h['_id']], $upv);
	return $h['server'];
    }

}
