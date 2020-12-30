<?php // create a UUID in an overkill, Rube Goldberg like manner.  

require_once('/opt/kwynn/kwutils.php');
require_once_ifex('/opt/kwynn/boot/mid.php');

class uuidcl {
    public static function get() {
	$o = new self();
    }
    
    private function __construct() {
	$this->d10();
	$this->p10();
	echo $this->raws;
    }

    
    private function p10() {
	$s = '';
	$i = 0;
	foreach($this->aa as $r) {
	    $s .= ++$i . ' *** ' . $r['note'] . "\n";
	    $b = nanopk();
	    $t = $r['f']();
	    $e = nanopk();
	    $s .= self::toString($t);
	    $d1 = $e['Uns'] - $b['Uns'];
	    $s .= number_format($d1) . 'ns';
	    $s .= ' / ';
	    $d2 = $e['tsc'] - $b['tsc'];
	    $s .= number_format($d2) . ' CPU ticks = ';
	    $s .= ' ';
	    
	    $rat = $d1 / $d2;
	    
	    $s .= sprintf('%0.30f', $rat); // attacker might infer boot time from clock drift *** !!!!! **** - so make this private / CLI but part of hash
	    
	    $s .= "\n";
	    $s .= number_format($e['Uns']) . ' - ' . number_format($b['Uns']) . ' core ' . $b['pid'] . ' then ' . $e['pid'];
	    
	    $s .= "\n";
	}
	
	$this->raws = $s;
    }
    
    public static function toString($din) {
	if (is_array($din)) {
	    $s = '';
	    foreach($din as $v) $s .= $v . "\n";
	    return $s;
	}
	else return $din . "\n";
	

    }
    
    private function d10() {
	$a = [
	    ['note' => 'MongoDB OID original and human-readable', 'f' => ['uuidcl', 'oid'], 'cl' => 'pub'],
	    ['note' => 'x86 TSC, PID - from my PHP extension', 'f' => 'rdtscp', 'cl' => 'cli'],
	    ['note' => 'nanotime - from my PHP extension', 'f' => 'nanotime', 'cl' => 'pub'],
	    ['note' => 'time, seconds', 'f' => 'time', 'cl' => 'pub'],
	    ['note' => 'process ID', 'f' => 'getmypid', 'cl' => 'cli'],
	    ['note' => 'machine ID stuff, my own', 'f' => ['uuidcl', 'mid'], 'cl' => 'cli'],
	    ['note' => 'rough-yet-absurdly precise machine locations' , 'f' => ['uuidcl', 'ec2_phy'], 'cl' => 'pub'],
	    ['note' => 'base62, in kwutils.php' , 'f' => 'base62', 'cl' => 'pub'],
	    ['note' => 'random_int()', 'f' => ['uuidcl', 'rint'], 'cl' => 'pub'],
	    ['note' => 'more random',  'f' => ['uuidcl', 'randrand'], 'cl' => 'pub'],	    
	    ];
	
	$this->aa = $a;
    }
    
    public static function randrand($csz = 65) {
	$l = random_int(70, 500);
	$s = base62($l);
	$i = 0;
	$r = '';
	do {
	    $r .= substr($s, $i, $csz) . "\n";
	    $i += $csz;
	} while ($i < $l);
	return trim($r);
    }
    
    public static function rint($min = PHP_INT_MIN, $max = PHP_INT_MAX, $number_format = true) {
	$n = random_int($min, $max);
	if (!$number_format) return $n;
	return number_format($n);
    }
    
    public static function mid() {
	$c = ['machine_id', 'get'];
	if (!(class_exists($c[0]))) return '';
	return $c();
    }
    
    public static function ec2_phy() {
	$k = '---DATA---';
	$s = '';
	$a = ['kwynn.com' => 'AWS_EC2_loc.txt', 'my local' => 'my_loc_dev_loc.txt'];
	foreach ($a as $lab => $loc) {
	    $t = file_get_contents(__DIR__ . '/' . $loc);
	    $b = strpos($t, $k);
	    $s .= $lab . "\n" . trim(substr($t, $b + strlen($k))) . "\n";
	}
	return trim($s);
    }
    
    public static function oid() {
	$o   = new MongoDB\BSON\ObjectId();
	$s   = $o->__toString();
	$r['o'] = $s;
	$ts  = $o->getTimestamp();
	$tss = date('Y-md-Hi-s', $ts);
	$fs  = $tss . '-' . sprintf('%08d', hexdec(substr($s  , 18    ))) . '-' . 
		    				   substr($s  ,  8, 10);
	$r['hu'] = $fs;
	return $r;
    }
    
}

if (didCLICallMe(__FILE__)) uuidcl::get();
