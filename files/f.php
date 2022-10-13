<?php

require_once('/opt/kwynn/kwutils.php');

class filePtrTracker extends dao_generic_3 {
	const dbname = 'files';
	const maxLnn = 200;
	const defaultNLines = 40;
	public readonly string $name;
	private readonly mixed $ohan;
	private readonly bool $collExists;
	private readonly array $oq;

	public function __construct(string $name) {
		$this->name = $name;
		$this->dbmg();
		$this->ohan = fopen($this->name, 'r');
		$this->getEndInit();
	}

	public function __destruct() { fclose($this->ohan);	}
	
	private function dbmg() {
		parent::__construct(self::dbname);
		$this->creTabs('files');
		$this->oq = ['_id' => $this->name];
	}
	
	private function getEndInit() {
		$res = $this->fcoll->findOne($this->oq);
		if (!$res) $this->setInitViaTail(self::defaultNLines);
		else fseek($this->ohan, $res['end']);
	}
	
	private function setInitViaTail() {
		$h = $this->ohan;
		fseek($h, -1 * self::defaultNLines * self::maxLnn, SEEK_END);
		$fn = ftell($h);
		if ($fn < 0) { fseek($h, 0, SEEK_SET); return 0; }
		kwas(fgets($h), 'throwaway line nonexistent'); // throw away because we may be in middle
	}
	
	private function setEndF() {
	
		if (!isset($this->collExists) && $this->fcoll->count($this->oq) === 0) {
			$dat['_id'] = $this->name;
			$this->fcoll->insertOne($dat);
		}  
		$this->collExists = true;
		$this->fcoll->upsert($this->oq, ['end' => ftell($this->ohan)]);
	}
	
	public function fgets() {
		$l = fgets($this->ohan);
		$this->setEndF();
		return $l;
	}
}

function testFPT() {
	$o = new filePtrTracker('/var/log/chrony/measurements.log');
	echo($o->fgets());
}

if (didCLICallMe(__FILE__)) testFPT();

exit(0);
