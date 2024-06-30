<?php

require_once('/opt/kwynn/kwutils.php');

class WWVB23 {
	
	const recordStderrMsg1 = "Recording WAVE 'stdin'";
	
	const bitsPerSampPerChan = 32; // note that a float 64 exists, but I don't have acccess
	const bitsPerByte = 8;
	const chan	   = 2;
	const sampr    = 16000; // watch multiples!!!
	const bitspersamptot   = self::bitsPerSampPerChan * self::chan;
	const bitsperS = self::bitspersamptot * self::sampr;
	const byperS = self::bitsperS * self::bitsPerByte;
	const totBits = self::bitsperS * self::duration;
				// 123456789
	const maxBuf = 10000000;
	const headerLen = 44;
	const unpackf = 'l'; // should be ok for x86
	
	const analIntervalS = 0.1;
	const logvdb = 1.26; // decibel
	// const logvdb = 1.023;
	
	const solds = 0.006629032; // speed of light delay in S
	
	const duration = 20;

	
	public function __construct() {
		$this->bypsapch = roint(self::bitsPerSampPerChan / self::bitsPerByte);
		$this->do10();
		$this->do20();
		$this->do30();
		$this->do40();
	}
	
	private function do1s($a) {
		
		$spb = roint(self::sampr * self::analIntervalS);
		
		$bi = 0;
		$i = 0;
		$tot = 0.0;

		$end = count($a);
		$min = PHP_INT_MAX;
		
		$tas = [];
		
		$tasn = [];
		
		for($i=0; $i < $end; $i += 2) {

			$tot += floatval($a[$i] + $a[$i+1]);
			for ($j = 0; $j < 2; $j++) if ($a[$i + $j] < $min) $min = $a[$i + $j];
			if (++$bi === $spb) {
				$tas [] = $tot;
				$tasn[] = $i * 2;
				$tot = 0.0;
				$bi = 0;


			}
			

		}

		$di = 0;
		foreach($tas as $i => $str) {
			
			$base = floatval(abs($min)) * floatval($tasn[$i]);
			
			$str += $base; // subtotraw
			// echo(sprintf('%e', (log($str, self::logvdb))) . "\n"); 
			// echo(number_format($str) . "\n"); 
			echo(sprintf('%e', $str) . "\n"); 
			$di++;
		}
		
		// echo('buckets displayed: ' . $di . "\n");
			
		
	}
	
	private function do40() {
		$a = $this->ora;
		$di = 0;
		$interval = self::sampr * self::chan;

		for ($i=0; isset($a[$i]); $i += $interval) {
			$sl = array_slice($a, $i, $interval);
			$this->do1s($sl); 
			echo("1s ***\n");
			$di++;
		}
		
		echo('sec n: ' . $di . "\n");	
	}
	
	private function do30() {
		
		$a = [];
		
		for ($i=0; $i < $this->obsz; $i += $this->bypsapch) {
			
			$s = substr($this->obuf, $i, $this->bypsapch);
			$ua = unpack(self::unpackf, $s);
			$n = $a[] = $ua[1];
			if ($i % 200 === 0) echo(number_format($n) . "\n"); // raw number
		}
		
		$this->ora = $a;
		echo('integers captured: ' . count($a) . "\n");
		
	}
	
	private function tout(array $a) {
		foreach($a as $u) {
			echo($u . ' ');
			$U = date('U', floor($u));
			$frs = strval($u - $U);
			kwas(substr($frs, 0, 2) === '0.', 'fraction time format sanity check fail - 0318');
			$frd = substr($frs, 2, 3);
			echo(date('H:i:s.', $U) . $frd . "\n");
		}
	}
	
	private function delay() {
		$s = microtime();
		$a = explode(' ', $s);
		$f = floatval($a[0]); 
		$sl = roint((1 - $f + self::solds) * M_MILLION);
		usleep($sl);
		
		echo($s . "\n");
		echo($sl . ' sleep' . "\n");
		
		kwas($f < 1, 'unexpected float val microtime 03:25');
	}
	
	private function do20() {
		
		$buf = '';
		fread($this->inh, self::headerLen);
		
		$this->delay();
		
		$ta = [];
		do {
			$ta[] = microtime(1);
			$t = fread($this->inh, self::maxBuf);
			$ta[] = microtime(1);
			if (!$t) break;
			$buf .= $t;
		} while ($t);
		
		$this->tout($ta);
		
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
		$cmd  = 'arecord -f S' . self::bitsPerSampPerChan . '_LE -c ' . self::chan . ' -r ' . self::sampr . ' --device="hw:0,0" ';
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
