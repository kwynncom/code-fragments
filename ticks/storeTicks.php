<?php

require_once('/opt/kwynn/mongodb2.php');
require_once(__DIR__ . '/../../tsnano/chronyParsed.php');

class ticks_tracker extends dao_generic_2 {
    const dbName = 'ticks';

    public function __construct() {
	parent::__construct(self::dbName, __FILE__);
	$this->creTabs(['t' => 'ticks', 'c' => 'chrony']);
	$this->p10();
	$this->ch10();

    }
    
    private function p10() {
	
	for($i=0; $i < 50; $i++) $npka[] = nanopk();
	for($i=0; $i < 50; $i++) $npka[$i]['_id'] = $this->tcoll->getSeq2('idoas');
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

new ticks_tracker();
