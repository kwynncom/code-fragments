<?php

require_once('/opt/kwynn/kwutils.php');
require_once('backoff.php');

class Qupdates extends dao_generic_3 {
	
	const url = 'https://qanon.pub/data/json/posts.json';
	const tmpf = '/tmp/qanon_pub_h.txt';
	const dbname = 'Qups';
	
	
	public static function get() {
		$o = new self();
		$res = $o->getI();
		return $res;
		
	}
	
	public function getI() { return $this->theDat;}
	
	private function __construct() {

		$this->theDat = [];
		$this->configBO();
		$this->initDB();
		$this->theDat   = $this->rget();
		
	}
	
	private function rget() {
		$a = microtime(1);
		$dat = $this->rget20();
		$b = microtime(1);
		$d = roint(($b - $a) * 1000);
		return ['dat' => $dat, 'fetch_ms' => $d];
	}
	
	private function rget20() {
		if (0 && file_exists(self::tmpf)) $t = file_get_contents(self::tmpf);
		else $t = $this->checkGetAndRecord();
		
		$a = $this->textToArr($t);
		if ($a) $this->p30($a);

		$res = $this->ucoll->find([], ['sort' => ['asof_ts' => -1], 'limit' => 3]);
		return $res;
	}
	
	private function textToArr($t) {
		if (!$t) return false;
		$a = $this->p10($t);
		$a20 = $this->p20($a);		
		return $a20;
		
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
		$a['_id'] = dao_generic_3::get_oids();
		$this->ucoll->insertOne($a, ['kwnoup' => true]);
		
	}
	
	private function p20($a) {
		$ret = $a;
		$ret['len_hu'] = number_format($a['len']);
		$ret['len'] = intval($ret['len']);
		$ret['asof_ts'] = strtotime($ret['asof_hu']);
		// $ret['lm_ts'] = strtotime();
		return $ret;
	}
	
	private function p10($t) {
		
		$ret = [];
		
		$htrc = intval(substr($t, 7, 3)); kwas($htrc >= 100 && $htrc <= 599, 'bad HTTP response code');
		
		$fs = ['len' => 'content-length', 'etag' => 'etag', 'lm_hu' => 'last-modified', 'asof_hu' => 'date'];

		if ($htrc === 304) { $ret['len'] = -1; unset($fs['len']); }
		
		$ret['htrc'] = $htrc;
		
		foreach($fs as $mynm => $f) {
			$re = '/' . $f . ': ([^\n]+)/';
			preg_match($re, $t, $ms);
			$ret[$mynm] = $ms[1];
			continue;
		}
		
		return $ret;
	}
	
	private function checkGetAndRecord() {
		if (!($ckr = $this->boffo->isok()))return FALSE;
		$hth = $this->getHTH();
		$res = $this->getActual($ckr, $hth);
		$this->boffo->putEvent();
		return $res;		
	}
	
	private function getHTH() {
		$ret = [];
		$res = $this->ucoll->findOne([], ['sort' => ['asof_ts' => -1]]);
		if (!$res) return $ret;
		$h[] = 'If-None-Match: ' . $res['etag'];
		// $h[] = 'If-Modified-Since: '	   . $res['lm_hu'];
		
		return $h;
	}
	
	private function getActual($cktok, $hth) {
		
		if ($cktok !== backoff::boffOKToken) return FALSE;
		
	
		$url = self::url;
		$p = self::tmpf;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url . '?t=' . roint(microtime(1) * 1000));
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		
		if ($hth) curl_setopt($ch, CURLOPT_HTTPHEADER, $hth);	
		
		
		$res = curl_exec($ch);
		$sz = strlen($res);
		
		// $ci = curl_getinfo($ch);
		
		file_put_contents($p, $res);
		return $res;
	}


}

Qupdates::get();