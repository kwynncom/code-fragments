<?php

require_once('/opt/kwynn/kwutils.php');

class bcebo extends dao_generic_3 {
	
	const dbname = 'btcprice';
	const url = 'https://api.coinbase.com/v2/prices/spot?currency=USD';
	
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
		$r['dhu'] = $this->theDHu;
		$r['fl'] = $p;
		$r['d' ] = '$' . number_format($p);
		$r['OK'] = true;
		$r['xms'] = $this->getx();
		kwjae($r);
	}
	
	private function getx() {
		$ms = (microtime(1) - $this->starte) * 1000;		
		$d  = sprintf('%0.2f', $ms);
		return $d;
	}
	
	public function __construct() {
		try {
			$this->starte = microtime(1);
			$this->config();
			$this->init10();
			parent::__construct(self::dbname);
			$this->creTabs('price');
			$this->theRget();
			$this->out();
			

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
	
	private function theRget() {
		if ($this->ck10() !== TRUE) return;
		
		// in PHP 8.1 results in ERROR: BTCpriceServer.php LINE: 64 - file_get_contents(): SSL operation failed with code 1. 
		// OpenSSL Error messages: error:0A000126:SSL routines::unexpected eof while reading ... BTCpriceServer.php
		// $r = file_get_contents('https://api.coinbase.com/v2/prices/spot?currency=USD');
		// https://github.com/php/php-src/pull/8558
		// appears to be in   php-8.1.7RC1, dated May 24, 2022
		// Ubuntu 22.04 is on PHP 8.1.2 as of June 5.
		
		$r = $this->viaCurl();
		$raw = json_decode($r, true);
		kwas($a = kwifs($raw, 'data'), 'bad result CB lookup 1 2142');
		kwas($a['base'] === 'BTC' && $a['currency'] === 'USD', 'bad result CB lookup 2 2143');
		$fl = floatval($a['amount']); kwas(is_numeric($fl), 'bad res CB lkup 3 2144');
		$dat['at'   ] = $sfl = microtime(1);
		$dat['price'] = $fl;
		$this->setovs($fl, $sfl);
		$dat['atr'  ] = date('r', roint($sfl));
		$this->pcoll->insertOne($dat);
		
	}
	
	private function viaCurl() {
		$cr = curl_init(self::url);
		curl_setopt($cr, CURLOPT_RETURNTRANSFER, 1); 
		$t  = curl_exec($cr);
		return $t;
		
	}

	private function setovs($p, $ts) { // can't do float|int in PHP 7.x!!! ; then int gives warning in PHP 8.1
		kwas(is_numeric($p), 'non numeric price setovs');
		$this->theP = $p;
		$this->theDHu = date('g:i:s A D', roint($ts));
	}
	
	private function setEarlier() {
		$r = $this->pcoll->findOne([], ['sort' => ['at' => -1]]);
		$this->setovs($r['price'], $r['at']);
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
				return TRUE;
			}

		}
		
		$this->setEarlier();
		
		return FALSE;
	}
	
}

if (didAnyCallMe(__FILE__)) new bcebo();
