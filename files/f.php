<?php

require_once('/opt/kwynn/kwutils.php');

class filePtrTracker extends dao_generic_3 {
	
	const maxLnn = 200;
	const dbname = 'files';
	public readonly string $name;
	private readonly mixed $ohan;
	private readonly int $endInit;
	private readonly bool $collExists;
	
	const defaultNLines = 40;
	
	public function __destruct() {
		fclose($this->ohan);
	}
	
	public function __construct(string $name) {
		$this->name = $name;
		$this->dbmg();
		$this->ohan = fopen($this->name, 'r');
		$this->getEndInit();
	}

	private function dbmg() {
		parent::__construct(self::dbname);
		$this->creTabs('files');
		$this->oq = ['_id' => $this->name];
	}
	
	private function getEndInit() {
		$res = $this->fcoll->findOne($this->oq);
		if (!$res) $this->tailI(self::defaultNLines);
		else $this->initEnd = $res['end'];
		fseek($this->ohan, $this->initEnd);
		
	}
	
	private function setEndF(int $endin = null) {
		
		$q = $this->oq;

		$ce = isset($this->collExists);
		if (!$ce && $this->fcoll->count($q) === 0) {
			$dat['_id'] = $this->name;
			$this->fcoll->insertOne($dat);
			$end = $this->initEnd;
		}  
		
		if ($endin) { 
			$end = $endin;
			if (!$ce) $this->initEnd = $end;
		}
		if (!$ce) $this->collExists = true;
		
		kwas(isset($end), 'setEndF end should be defined here');
		$this->fcoll->upsert($q, ['end' => $end]);
		if (!isset($this->ofptr)) $this->ofptr = $end;
	}
	
	public function fgets() {
		$l = fgets($this->ohan);
		$this->setEndF(ftell($this->ohan));
		return $l;
	}
	
	private function tailI() {
		$h = $this->ohan;
		fseek($h, 0, SEEK_END);
		$end = ftell($h);
		if ($end === 0) { $this->initEnd = 0; return; }
		
		fseek($h, -1 * self::defaultNLines * self::maxLnn, SEEK_END);
		$fn = ftell($h);
		if ($fn < 0) { fseek($h, 0, SEEK_SET); $this->initEnd = 0; return; }
		kwas(fgets($h), 'throwaway line nonexistent'); // throw away because we may be in middle
		$this->initEnd = ftell($h);
	}
}

function testFPT() {
	$o = new filePtrTracker('/var/log/chrony/measurements.log');
	echo($o->fgets());
}

if (didCLICallMe(__FILE__)) testFPT();




exit(0);
