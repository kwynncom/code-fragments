<?php

require_once('/opt/kwynn/kwutils.php');

class backoff extends dao_generic_3 {
	
	const dbname = 'backoff';
	const boffOKToken = 'iAmBackOffOKToken';
	

	public function __construct(string $eventTypeID, array $backOffArrayInMinutes) {
		parent::__construct(self::dbname);
		$this->creTabs('events');
		$this->etype = $eventTypeID;
		$this->seta($backOffArrayInMinutes);
	}
	
	private function seta($ain) {
		foreach($ain as $v) $m[] = $v * 60; unset($v, $ain);
		$this->boa = $m; unset($m);
		$this->boan = count($this->boa);
		$this->locko = new sem_lock(__FILE__);
		$this->boasum = array_sum($this->boa);
	}
	
	public function isok() {
		$this->locko->lock();		
		$res = $this->isok20();
		$this->locko->unlock();
		
		if ($res) return self::boffOKToken;
		return FALSE;
	}
	
	public function putEvent() {
		$dat = [];
		$dat['usbo'] = microtime(1);
		$dat['_id']  = $this->etype . '_' . dao_generic_3::get_oids();
		$dat['type'] = $this->etype;
		$this->ecoll->insertOne($dat);
	}
	
	private function isok20() {
		$now = time();
		$sum = $this->boasum;
		$tq = ['type' => $this->etype];
	
		for($j=0; $j < 2; $j++) {
			if ($j > 0) {
				$sum = 0;
				for($i=0; ($i < $this->boan && $i < $cnt); $i++) $sum += $this->boa[$i];
			}
			
			$d = $now - $sum;
			$q = ['usbo' => ['$gte' => $d]];
			$q = kwam($q, $tq);
			

			$cnt = $this->ecoll->count($q);

			if ($cnt === 0) {
				if ($j === 0) $this->ecoll->deleteMany($tq);
				return TRUE;
			}

		}
		
		return FALSE;		
	}
	
}