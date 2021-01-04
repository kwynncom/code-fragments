<?php

require_once('/opt/kwynn/mongodb2.php');
require_once(__DIR__ . '/../../tsnano/chronyParsed.php');

class ticks_tracker extends dao_generic_2 {
    const dbName = 'ticks';
    const sleep = 3;
    const datv  = 4;
    const doFor = 3500;

    public function __construct($fromChild = false) {
	parent::__construct(self::dbName, __FILE__);
	$this->creTabs(['t' => 'ticks', 'c' => 'chrony', 'm' => 'meta']);
	if ($fromChild) return;
	$this->p10();
    }
    
    private function p10() {
	$until = time() + self::doFor;
	$i = 1;
	
	do {
	    $this->p20();
	    $this->ch10();
	    $sl = pow(self::sleep, $i);
	    $tt = time() + $sl;
	    if ($tt > $until) break;
	    sleep($sl);
	} while($i++ < 20);
    }
    
    private function p20() {
	$r = getStableNanoPK();
	$d = $r['d'];
	$d['datv'] = self::datv;
	$seq = $this->tcoll->getSeq2('idoas');
	$d['_id' ] = $seq;
	$this->tcoll->insertOne($d);

    }
    
    private function ch10() {
	$c = chrony_parse::get(true);
	unset($c['first_server_timestamp'], $c['last_server_timestamp'], $c['cmd']);
	$c['_id'] = $this->ccoll->getSeq2('idoas');
	$c['atns'] = nanotime();
	$this->ccoll->insertOne($c);
	return;
    }
}

if (didCLICallMe(__FILE__)) new ticks_tracker();
