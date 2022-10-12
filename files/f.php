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
		if (!$res) $this->tailI(self::defaultNLines);
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
	
	public static function getEnd(string $name) {
		$o = new self($name, 'getEnd');
		if (isset($o->retString)) {
			fclose($o->ohan);
			return $o->retString;
		}
		else return $o->ohan;
	}
	
	private function tailI(int $tn) {
		$h = $this->ohan;
		fseek($h, 0, SEEK_END);
		$this->end = $end = ftell($h);
		fseek($h, -1 * $tn * self::maxLnn, SEEK_END);
		$fn = ftell($h);
		if ($fn < 0) fseek($h, 0);
		kwas(fgets($h), 'throwaway line nonexistent'); // throw away because we may be in middle
		
		$start = ftell($h); kwas($start < $end, 'no valid starting point file pointer util');
		$len = $end - $start;
		$res = fread($h, $len); kwas($res[$len - 1] === "\n", 'last char should be newline');
		$this->retString = $res;
		
	}
}

$test = filePtrTracker::getEnd('/var/log/chrony/measurements.log', 40);
exit(0);
