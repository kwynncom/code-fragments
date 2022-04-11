<?php

require_once('/opt/kwynn/kwutils.php');

class bcebo extends dao_generic_3 {
	
	const dbname = 'btcprice';
	
	private function config() {
		$this->boa  = [1, 1, 1, 2, 3, 5, 10, 15, 20, 30, 60];
	}
	
	private function init10() {
		$this->locko = new sem_lock(__FILE__);
		$this->boasum = array_sum($this->boa);
	}
	
	public function __construct() {
		$this->config();
		$this->init10();
		parent::__construct(self::dbname);
		$this->creTabs(['reqs', 'price']);
		$this->ck10();
		$this->theget();
		$this->locko->unlock();
	}
	
	private function theget() {
		$r = file_get_contents('https://api.coinbase.com/v2/prices/spot?currency=USD');
		$a = json_decode($r, true);
		if ($a) $this->pcoll->insertOne($a);
		
	}
	
	private function ck10() {
		$this->locko->lock();
		if ($this->pcoll->count() === 0) return;
		kwas(false, 'paranoid 1 2138');
	}
	
}

if (didAnyCallMe(__FILE__)) new bcebo();
