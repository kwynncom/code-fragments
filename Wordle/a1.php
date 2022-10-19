<?php

require_once('config.php');

class Wordle_analysis_10 {
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		$all = json_decode(file_get_contents(WordleConfig::listJSON), 1);
		$cnt = count($all);
		$wl = WordleConfig::wordLength;
		$els = [];
		for ($i = 0; $i < $cnt; $i++)
		for ($j = 0; $j < $cnt; $j++) {
			$a = $all[$i];
			$b = $all[$j];
			for ($k=0; $k < $wl; $k++)
			for ($l=0; $l < $wl; $l++)
				if ($a[$k] === $b[$l]) {
					if (!isset($els[$a])) $els[$a] = 0;
					$els[$a]++;
					continue 3;
				}
				
			$ignoreMe = true;
			
		}
		
		file_put_contents('./bulkData/a1.json', json_encode($els, JSON_PRETTY_PRINT));
		
		return;
	}
}

new Wordle_analysis_10();