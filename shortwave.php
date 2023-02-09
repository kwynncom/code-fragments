<?php

require_once('/opt/kwynn/kwutils.php');

class WWVB23 {
	
	const recordStderrMsg1 = "Recording WAVE 'stdin'";
	
	const bitsPerSampChan = 32; // note that a float 64 exists, but I don't have acccess
	const bitsPerByte = 8;
	const chan	   = 2;
	const sampr    = 8000;
	const bitspersamptot   = self::bitsPerSampChan * self::chan;
	const bitsperS = self::bitspersamptot * self::sampr;
	const totBits = self::bitsperS * self::duration;
				// 123456789
	const maxBuf = 10000000;
	const headerLen = 44;
	const unpackf = 'l'; // should be ok for x86

	const duration = 1;
	const analIntervalS = 0.1;
	
	public function __construct() {
		$this->bypsa = roint(self::bitsPerSampChan / self::bitsPerByte);
		$this->do10();
		$this->do20();
		$this->do30();
		$this->do40();
	}
	
	private function do40() {
		$a = $this->i2ct;
		$fa = [];
		
		$spb = roint(self::sampr * self::analIntervalS);
		
		$bi = 0;
		$i = 0;
		$f = 0.0;
		$di = 0;
		foreach($a as $r) {
			$f += $r;
			if (++$bi === $spb) {
				echo(number_format($f) . "\n"); $di++;
				$f = 0.0;
				$bi = 0;

			}
		}
		
		echo('bucket n: ' . $di . "\n");
		
	}
	
	private function do30() {
		$a = unpack(self::unpackf, $this->obuf);
		$end = $this->obsz >> 1; // halve it
		$inc = $this->bypsa * self::chan;
		
		$di = 0;
		$i2ct = [];
		for ($i=0; $i < $this->obsz; $i += $inc) {
			$ua = [];
			for ($j=0; $j < self::chan; $j++) {
				$s = substr($this->obuf, $i + ($j * $this->bypsa), $this->bypsa);
				$ua[$j] = unpack(self::unpackf, $s);
			}
			
			$int = abs($ua[0][1]) + abs($ua[1][1]);
			$i2ct[$di++] = floatval($int);
		}
		
		$this->i2ct = $i2ct;
		echo('integers calced: ' . $di . "\n");
		
	}
	
	private function do20() {
		
		$buf = '';
		fread($this->inh, self::headerLen);
		
		while($t = fread($this->inh, self::maxBuf)) $buf .= $t;
		
		$exp = roint(self::totBits / self::bitsPerByte);
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
