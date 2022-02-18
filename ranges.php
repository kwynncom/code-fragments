<?php

require_once('/opt/kwynn/kwutils.php');

class mcrT20 {
	
	const maxcpus = 600; // AWS has a 192 core processor as of early 2022

	public static function CPUCount() { return self::getValidCPUCount(shell_exec('grep -c processor /proc/cpuinfo'));   }
    
    public static function getValidCPUCount($nin) {
		$nin = trim($nin);
		kwas(is_numeric($nin), 'cpu count not a number');
		$nin = intval($nin);
		kwas($nin >= 1 && $nin <= self::maxcpus, 'invalid number of (hyper)threads / cores / cpus');
		return $nin;
	}

public static function vse($v) {
	kwas(is_numeric($v), 'not a number vse kw');
	$v = intval($v);
	kwas($v >= 0, 'not 0 or postive int vse kw');
	return $v;
	
}

private function getInc(int $s, int $e) {
	$n = $this->theon;
	$d = $e - $s;
	$i = roint($d / $n);
	if ($i < 1) return 1;
	return $i;
}

private function __construct(int $s, int $e, int $n) {
	$this->setN($n);
	$tres = $this->do10($s, $e);
	$this->setVOR($tres, $s, $e);
}

private function setVOR(array $a, int $s, int $e) {
	kwas($a && is_array($a), 'bad array ranges kw');
	$an = count($a); kwas($an >= 1 && $an <= $this->theon, 'bad array count kw ranges');
	kwas($a[0]['l'] === $s, 'bad start ranges kw');
	kwas($a[$an - 1]['h'] === $e, 'bad end ranges kw');
	
	for($i=0; $i < $an; $i++) {
		
		
	}
	
	$this->oares = $a;
	
	
}

public static function get(int $s, int $e, int $n = 0) {
	$o = new self($s, $e, $n);
	return $o->getR();
}

private function setN(int $n) {
	if (!$n) $n = self::CPUCount(); kwas(self::getValidCPUCount($n), 'bad cpu / divide by count');
	$this->theon = $n;
	
}

public function getR() {  return $this->oares;  }

public function do10(int $s, int $e) {

	self::vse($s); self::vse($e);
	kwas($e >= $s, 'start end reversed');
	$inc = $this->getInc($s, $e);

	$ite = 0;
	$res = [];
	for ($ite = 0, $l=$s; $ite < $this->theon; $ite++) {
		unset($s);

/*		if ($l >= $e) {
			$res
		} */
		
		$res[$ite]['l'] = $l;
		$th = $l + $inc;
		if ($th >= $e) {
			$res[$ite]['h'] = $e;
			return $res;
		}
		$res[$ite]['h'] = $th;
		$l = $th + 1;
		
	}
	

	
	
	
	
}

    public static function tests() {
	$ts = [
		// [-1,0],
		// [0,0, 0],
		[0,0],
		[1,2],
		[1592696603, 1603313775],
		[1, 284717],
		[0, 0],
		[1, 1],
		[1, 2, 4],
	    	[0, 2, 1],
	    	[1, 2, 1],
		[1, 0],
		[1, 4, 6],
		[12,1],
		[1, 6],
	        [0, 1],
		[0, 200],
		
	    ];
	

	$max = count($ts) - 1;
	
	for ($i=0; $i <= 0; $i++) {
	$t = $ts[$i];
	if (!isset($t[2])) $t[2] = 12;
	try {
	    $res = self::get($t[0], $t[1], $t[2]);
	    $out = [];
	    $out['in'] = $t;
	    $out['out'] = $res;
	    print_r($out);
	} catch (Exception $ex) {
	    throw $ex;
	}
	}
    } // func
	
}

if (didCLICallMe(__FILE__)) mcrT20::tests();
