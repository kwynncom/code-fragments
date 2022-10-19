<?php

require_once('./../config.php');

class wordle_parse_list {
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		$t = file_get_contents('wordle_list.html'); // https://www.wordunscrambler.net/word-list/wordle-word-list
		$l = strlen($t);
		$d = getDOMO($t); unset($t, $l);
		$lis = $d->getElementsByTagName('li');
		$ws = [];
		foreach($lis as $li) {
			$as = $li->getElementsByTagName('a');
			foreach($as as $a) {
				$in = $a->textContent;
				preg_match('/^[a-z]{5}$/', $in, $ms);
				$w = kwifs($ms, 0);
				if (!$w) continue;
				$ws[] = $w;
				continue;
			}
			
		}
		
		kwas(count($ws) === 2309, 'mismatch to expected Wordle list count');
		
		
		file_put_contents(WordleConfig::listJSON, json_encode($ws, JSON_PRETTY_PRINT));
		file_put_contents(WordleConfig::listTXT , implode("\n", $ws) . "\n");
		
		foreach([WordleConfig::listJSON, WordleConfig::listTXT] as $f) chmod($f, 0400);
	}
}

new wordle_parse_list();