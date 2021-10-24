<?php

require_once('/opt/kwynn/mongodb2.php');
require_once('/opt/kwynn/lock.php');

class nist_servers extends dao_generic_2 {

    const dbName = 'time21';
	const srvn = 7;
	const ip4n = 5;
	const ip6n = 2;
	const ipbase = 26;
	const org = 'NIST';
	
	private function __construct() {
		$this->locko = new sem_lock(__FILE__);
		$this->locko->lock();
		parent::__construct(self::dbName, __FILE__);
		$this->creTabs(['s' => 'servers']);
		$this->insertSrvs();
	}
	
	public function __destruct() { $this->locko->unlock(); } // must be public or permission error; not entirely sure why
	
	private function insertSrvs() {
		if ($this->scoll->count() === self::srvn) return;
		
		$this->scoll->createIndex(['id' => 1], ['unique' => true]);
		$this->scoll->createIndex(['ip' => 1], ['unique' => true]);

		$base4 = '129.6.15.';
		$base6 = '2610:20:6f15:15::';
		$si   = self::ipbase;
		for ($i=0; $i < self::ip4n; $i++, $si++) {
			if (true		    ) $this->ins1($base4, $si);
			if ($i < self::ip6n ) $this->ins1($base6, $si);
		}
		
		kwas($this->scoll->count() === self::srvn, 'did not create NIST servers');
		return;
	}
	
	private function ins1($base, $i) {
		$ip = $base . $i;
		$dat = [];
		$dat['org' ] = self::org;
		$dat['ip'] = $ip;
		$dat['id'] = $dat['_id'] = self::org . '-' . 'v' . (strlen($ip) === 11 ? 4 : 6) . '-' . $i;
		$this->scoll->insertOne($dat);	
	}
	
	public static function regGet() {
		$o = new self();
	}
}

if (didCLICallMe(__FILE__)) nist_servers::regGet();
