<?php

require_once('/opt/kwynn/kwutils.php');

class WWVB23 {
	
	const recordStderrMsg1 = "Recording WAVE 'stdin'";
	
	const bitsPerSampChan = 32; // note that a float 64 exists, but I don't have acccess
	const chan	   = 2;
	const sampr    = 8000;
	const bitspersamptot   = self::bitsPerSampChan * self::chan;
	const bitsperS = self::bitspersamptot * self::sampr;
	const duration = 5;
	const totBits = self::bitsperS * self::duration;
				// 123456789
	const maxBuf = 10000000;
	const headerLen = 44;

	
	public function __construct() {
		$this->do10();
		$this->do20();
		$this->do30();
	}
	
	private function do30() {
		for ($i=0; $i < $this->obsz; $i++) {
			
		}
	}
	
	private function do20() {
		
		$buf = '';
		fread($this->inh, self::headerLen);
		
		while($t = fread($this->inh, self::maxBuf)) $buf .= $t;
		
		$exp = roint(self::totBits / 8);
		$rcv = strlen($buf);
		echo('Expected: ' . $exp .  "\n");
		echo('Received: ' . $rcv . "\n");
		kwas($exp === $rcv, 'bytes not as expected 0206');
		
		$this->obuf = $buf;
		$this->obsz = $rcv;
		
		
		return;
	}
	
	private function do10() {
		$cmd  = 'arecord -f S' . self::bitsPerSampChan . '_LE -c ' . self::chan . ' -r ' . self::sampr . ' --device="hw:0,0" ';
		$cmd .= ' -d ' . self::duration;

		echo($cmd . "\n");
		
		$pd = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];

		$this->inpr = proc_open($cmd, $pd, $this->pipes);
		$this->inh  = $this->pipes[1];
		$this->checkOpen();

	}
	
	private function checkOpen() {
		$s = fgets($this->pipes[2]);
		$key = self::recordStderrMsg1;
		kwas(substr($s, 0, strlen($key)) === $key, 'did not get message: ' . $key);
    }
	
}

new WWVB23();
