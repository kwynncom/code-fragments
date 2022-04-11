<?php

require_once('/opt/kwynn/kwutils.php');

class bcebo extends dao_generic_3 {
	
	const dbname = 'btcprice';
	
	private function config() {
		$s = [1, 1, 1, 2, 3, 5, 10, 15, 20];
		
		foreach($s as $v) $m[] = $v * 60;
		$this->boa = $m;
		$this->boan = count($this->boa);
	}
	
	private function init10() {
		$this->locko = new sem_lock(__FILE__);
		$this->boasum = array_sum($this->boa);
	}
	
	private function out() {
		kwas($p = kwifs($this, 'theP'), 'no valid price value');
		
	}
	
	public function __construct() {
		try {
			$this->config();
			$this->init10();
			parent::__construct(self::dbname);
			$this->creTabs('price');
			$this->ck10();
			$this->theget();

		} catch(Exception $ex) { 
			echo($ex->getMessage());
		}
	}
	
	public function __destruct() {
		if (isset($this->locko)) {
			$r =  $this->locko->unlock();		
			kwynn();
		}
	}
	
	private function theget() {
		$r = file_get_contents('https://api.coinbase.com/v2/prices/spot?currency=USD');
		$raw = json_decode($r, true);
		kwas($a = kwifs($raw, 'data'), 'bad result CB lookup 1 2142');
		kwas($a['base'] === 'BTC' && $a['currency'] === 'USD', 'bad result CB lookup 2 2143');
		$fl = floatval($a['amount']); kwas(is_numeric($fl), 'bad res CB lkup 3 2144');
		$this->theP = $dat['price'] = $fl;
		$dat['at'   ] = $sfl = microtime(1);
		$dat['atr'  ] = date('r', $sfl);
		$this->pcoll->insertOne($dat);
		
	}
	
	private function ck10() {
		$now = time();
		$sum = $this->boasum;
		
		$this->locko->lock();
		for($j=0; $j < 2; $j++) {
			if ($j > 0) {
				$sum = 0;
				for($i=0; ($i < $this->boan && $i < $cnt); $i++) $sum += $this->boa[$i];
			}
			
			$d = $now - $sum;
			$q = ['at' => ['$gte' => $d]];
			
			$cnt = $this->pcoll->count($q);
			if ($cnt === 0) {
				if ($j === 0) $this->pcoll->drop();
				return;
			}

		}
		
		kwas(false, 'over quota');
	}
	
}

if (didAnyCallMe(__FILE__)) new bcebo();
