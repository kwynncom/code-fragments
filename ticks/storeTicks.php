<?php

require_once('/opt/kwynn/mongodb2.php');
require_once(__DIR__ . '/../../tsnano/chronyParsed.php');

class ticks_tracker extends dao_generic_2 {
    const dbName = 'ticks';
    const samsize = 50;
    const tosssize = 4;
    const sleep = 3;
    const datv  = 2;

    public function __construct($fromChild = false) {
	parent::__construct(self::dbName, __FILE__);
	$this->creTabs(['t' => 'ticks', 'c' => 'chrony']);
	if ($fromChild) return;
	$this->p10();
	$this->ch10();

    }
    
    private function p10() {
	
	for($i=0; $i < self::tosssize; $i++) nanopk();
	$t = nanopk();
	$t['init'] = true;
	$npka[] = $t;
	sleep(self::sleep);
	
	for($i=1; $i < self::samsize; $i++) $npka[] = nanopk();
	for($i=0; $i < self::samsize; $i++) {
	    $npka[$i]['_id'] = $this->tcoll->getSeq2('idoas');
	    $npka[$i]['datv'] = self::datv;
	}
	$this->tcoll->insertMany($npka);	
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
