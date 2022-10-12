<?php

require_once('/opt/kwynn/kwutils.php');

class filePtrTracker extends dao_generic_3 {
	
	const maxLnn = 200;
	const dbname = 'files';
	public readonly string $name;
	public readonly string $retString;
	public readonly mixed $ohan;
	public readonly int $end;
	public readonly array $oq;
	const defaultNLines = 40;
	
	private function __construct(string $name, string $op, int $n = null) {
		$this->name = $name;
		$this->dbmg();
		$this->ohan = fopen($this->name, 'r');
				
		if ($op === 'tail') {
			$this->tailI($n);
			$this->register();
			return;
		} 
		
		if ($op === 'getEnd') {
			$this->getEndI();
			return;
		}
	}
	
	
	
	private function dbmg() {
		parent::__construct(self::dbname);
		$this->creTabs('files');
		$this->oq = ['_id' => $this->name];
	}
	
	private function getEndI() {
		$res = $this->fcoll->findOne($this->oq);
		if (!$res) $this->tailI(self::defaultNLines, false);
		else $this->end = $res['end'];
	}
	
	private function register() {
		$q = $this->oq;
		if ($this->fcoll->count($q) === 0) {
			$dat['_id'] = $this->name;
			$this->fcoll->insertOne($dat);
		}
		$this->fcoll->upsert($q, ['end' => $this->end]);
	}
	
	public function getEnd(string $name) {
		$o = new self($name, 'getEnd');
	}
	
	private function tailI(int $tn, bool $trueEnd = true) {
		$h = $this->ohan;
		fseek($h, 0, SEEK_END);
		$end = ftell($h);
		if ($trueEnd) $this->end = $end;
		fseek($h, -1 * $tn * self::maxLnn, SEEK_END);
		$fn = ftell($h);
		if ($fn < 0) fseek($h, 0);
		kwas(fgets($h), 'throwaway line nonexistent'); // throw away because we may be in middle
		
		$start = ftell($h); kwas($start < $end, 'no valid starting point file pointer util');
		if (!$trueEnd) $this->end = $start;
		$len = $end - $start;
		if ($trueEnd) {
			$res = fread($h, $len); kwas($res[$len - 1] === "\n", 'last char should be newline');
		}
		fclose($h);
		
		$this->retString = $res;
		
	}
	
	
	public static function tail(string $name, int $n) {
		$o = new self($name, 'tail', $n);
		return $o->retString;
	}
}

$test = filePtrTracker::tail('/var/log/chrony/measurements.log', 40);
exit(0);
