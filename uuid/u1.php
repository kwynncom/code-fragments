<?php // create a UUID in an overkill, Rube Goldberg like manner.  

require_once('/opt/kwynn/kwutils.php');
require_once_ifex('/opt/kwynn/boot/mid.php');

class uuidcl {
    
    private $oprs = '';
    
    public static function get() {
	$o = new self();
    }
    
    private function __construct() {
	$this->d10();
	$this->p10();
	echo $this->raws;
    }

    private function hpr10(&$s, $pr) {
	$this->oprs .= $pr;
	if (PHP_SAPI === 'cli') $s .= $pr;
    }
    
    private function p10() {
	$s = '';
	$i = 0;
	foreach($this->aa as $r) {
	    $lpr = '';
	    $s .= ++$i . ' *** ' . $r['note'] . "\n";
	    $b = nanopk();
	    $t = $r['f']();
	    $e = nanopk();
	    $s .= self::toString($t);
	    $d1 = $e['Uns'] - $b['Uns'];
	    $d2   = $e['tsc'] - $b['tsc'];
	    $rat  = $d1 / $d2;
	    $s   .= 'diff ns / diff CPU rounded = ' . round($rat, 1) . "\n";
	    
	    $lpr .= number_format($e['tsc']) . "\n";
	    $lpr .= number_format($b['tsc']) . "\n";
	    
	    $nfd2 =  number_format($d2);
	    
	    $lpr .= sprintf('%18s', $nfd2) . "\n";
    
	    $nfd1 =  number_format($d1);
	    
//	    $lpr .= sprintf('%0.30f', $rat); // attacker might infer boot time from clock drift *** !!!!! **** - so make this private / CLI but part of hash
	    
	    $s .= number_format($e['Uns']) . "\n";
	    $s .= number_format($b['Uns']) . "\n";
	    $s .= sprintf('%25s', $nfd1) . "\n";
	    $s .= 'core ' . $b['pid'] . ' / ' . $e['pid'];
	    $s .= "\n";
	    
	    $this->hpr10($s, $lpr);
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
    
    public static function time() { 
	return number_format(time());
    }
    
    private function d10() {
	$a = [
	    ['note' => 'MongoDB OID original and human-readable', 'f' => ['uuidcl', 'oid']],
	    ['note' => 'time, seconds', 'f' => ['uuidcl', 'time']],
	    ['note' => 'process ID', 'f' => [$this, 'pid']],
	    ['note' => 'machine ID stuff, my own', 'f' => ['uuidcl', 'mid']],
	    ['note' => 'rough-yet-absurdly precise machine locations' , 'f' => ['uuidcl', 'ec2_phy']],
	    ['note' => 'base62, in kwutils.php' , 'f' => 'base62'],
	    ['note' => 'random_int()', 'f' => ['uuidcl', 'rint']],
	    ['note' => 'more random',  'f' => ['uuidcl', 'randrand']],	    
	    ];
	
	$this->aa = $a;
    }
    
    private function pid() {
	$pid = getmypid();
	$aud = 'process ID is integer and >= 1, included in string';
	kwas(is_integer($pid) && $pid >= 1, $aud);
	$this->oprs .= 'process id = ' . $pid . "\n";
	return $aud;
    }
    
    public static function randrand($csz = 65) {
	$l = random_int(70, 500);
	// $s = base62($l);
	$s = base64_encode(random_bytes($l)); // much, much faster than my base62()
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
	$a = $c();
	$s = '';
	$s .= 'machine ID hash of hash: ' . "\n" . $a['hash_of_hash'] . "\n";
	$s .= 'machine ID # of private fields hashed = ' . $a['private_field_count'] . "\n";
	$s .= 'machine ID ' . $a['midv'];
	return $s;
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
