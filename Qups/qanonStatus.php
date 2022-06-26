<?php

require_once('/opt/kwynn/kwutils.php');
require_once('backoff.php');

class Qupdates extends dao_generic_3 {
	
	const url = 'https://qanon.pub/data/json/posts.json';
	const tmpf = '/tmp/qanon_pub_h.txt';
	const dbname = 'Qups';
	
	
	public function __construct() {
		cliOrDie();
		$this->configBO();
		$this->initDB();
		$t   = $this->rget();
		$a   = $this->p10($t); unset($t);
		$a20 = $this->p20($a); unset($a);
		$this->p30($a20);
		
	}

	private function boffok() {
		
		
	}
	
	private function rget() {
		if (1) {
			if (!$this->boffo->isok()) kwas(false, 'quota fail');
			if (0 && file_exists(self::tmpf)) return file_get_contents(self::tmpf);
		}
		
		$res = $this->getActual();
		$this->boffo->putEvent();
		return $res;
	}
	
	private function initDB() {
		parent::__construct(self::dbname);
		$this->creTabs('ups');
	}
	
	private function configBO() { // https://github.com/kwynncom/code-fragments/blob/262f30b067e1e88ec64489dd0e849107d6c201d4/btcpr/BTCpriceServer.php
		$a = [1, 1, 3, 5, 10, 15, 20];
		$this->boffo = new backoff('Qups', $a);
	}

	private function p30($a) {
		
		// $a['usbo'] = microtime(1);
		$a['_id'] = dao_generic_3::get_oids();
		$this->ucoll->insertOne($a, ['kwnoup' => true]);
		
	}
	
	private function p20($a) {
		$ret = $a;
		$ret['etag'] = str_replace('"', '', $a['etag']);
		$ret['len_hu'] = number_format($a['len']);
		// $ret['lm_ts'] = strtotime();
		return $ret;
	}
	
	private function p10($t) {
		
		$ret = [];
		$fs = ['len' => 'content-length', 'etag' => 'etag', 'lm_hu' => 'last-modified', 'asof_hu' => 'date'];
		foreach($fs as $mynm => $f) {
			$re = '/' . $f . ': ([^\n]+)/';
			preg_match($re, $t, $ms);
			$ret[$mynm] = $ms[1];
			continue;
		}
		
		return $ret;
	}
	

	
	private function getActual() {
		
		cliOrDie();
	
		$url = self::url;
		$p = self::tmpf;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url . '?t=' . roint(microtime(1) * 1000));
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		$res = curl_exec($ch);
		$sz = strlen($res);
		file_put_contents($p, $res);
		return $res;
	}


}

new Qupdates();