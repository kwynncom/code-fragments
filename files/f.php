<?php

require_once('/opt/kwynn/kwutils.php');

class filePtrTracker extends dao_generic_3 {
	
	const maxLnn = 200;
	const dbname = 'files';
	public readonly string $name;
	
	private function __construct(string $name, string $op, int $n = null) {
		$this->dbmg();
		$this->name = $name;
		if ($op === 'tail') $this->tailI($n);
	}
	
	private function dbmg() {
		parent::__construct(self::dbname);
		$this->creTabs('files');
		$this->fcoll->createIndex(['name' => 1], ['unique' => true]);
	}
	
	private function register(int $end) {
		$this->fcoll->upsert(['name' => $this->name], ['ptr' => $end]);
	}
	
	private function tailI(int $tn) {
		$h = fopen($this->name, 'r');
		fseek($h, 0, SEEK_END);
		$end = ftell($h);
		fseek($h, -1 * $tn * self::maxLnn, SEEK_END);
		$fn = ftell($h);
		if ($fn < 0) fseek($h, 0);
		kwas(fgets($h), 'throwaway line nonexistent'); // throw away because we may be in middle
		
		$start = ftell($h); kwas($start < $end, 'no valid starting point file pointer util');
		$len = $end - $start;
		$res = fread($h, $len); kwas($res[$len - 1] === "\n", 'last char should be newline');
		$this->register($end);
		// echo($res);
		
				
		
	}
	
	
	public static function tail(string $name, int $n) {
		$o = new self($name, 'tail', $n);
	}
}

filePtrTracker::tail('/var/log/chrony/measurements.log', 40);
