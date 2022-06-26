<?php

require_once('/opt/kwynn/kwutils.php');

class Qupdates {
	
	const url = 'https://qanon.pub/data/json/posts.json';
	const tmpf = '/tmp/qanon_pub_h.txt';
	
	
	public function __construct() {
		cliOrDie();
		$t = $this->rget();
		$this->p10($t);
	}

	private function p10($t) {
		
		$fs = ['content-length', 'etag', 'last-modified', 'date'];
		foreach($fs as $f) {
			$re = '/' . $f . ': ([^\n]+)/';
			preg_match($re, $t, $ms);
			continue;
		}
		
		return;
	}
	
	private function rget() {
		if (file_exists(self::tmpf)) return file_get_contents(self::tmpf);
		$this->getActual();		
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
	}


}

new Qupdates();