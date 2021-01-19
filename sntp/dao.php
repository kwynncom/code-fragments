<?php

require_once('/opt/kwynn/kwutils.php');
require_once('/opt/kwynn/mongodb2.php');

class dao_ntp_pool_quota extends dao_generic_2 {
    
    const dbName = 'sntp2';
    
    public function __construct($sin) {
	parent::__construct(self::dbName, __FILE__);
	$this->colls = ['u' => 'use', 's' => 'servers', 'p' => 'pools'];
	$this->creTabs($this->colls);
	$this->init($sin);
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
		$this->$tc->insertOne($r);
	    }
	}
    }
    
    
    public function get() {
	$now = microtime(1);
	$ps = $this->pcoll->findOne(['$expr' => ['$lt' => ['$lts', ['$subtract' => [$now, '$minpoll']]]]], ['$sort' => ['lts' => 1]]);
	return;
    }

}
