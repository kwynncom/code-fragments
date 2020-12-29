<?php // create a UUID in an overkill, Rube Goldberg like manner.  

require_once('/opt/kwynn/kwutils.php');
include_exists('/opt/kwynn/boot/mid.php');

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
	foreach($this->aa as $r) {
	    $b = nanotime();
	    $t = $r['f']();
	    $e = nanotime();
	    $s .= self::toString($t);
	    $s .= number_format($e - $b);
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
	    ['note' => 'MongoDB OID original and human-readable', 'f' => ['uuidcl', 'getoid'], 'cl' => 'pub'],
	    ['note' => 'my own extension - TSC, PID', 'f' => 'rdtscp', 'cl' => 'cli'],
	    ['note' => 'my own extension - nanotime', 'f' => 'nanotime', 'cl' => 'pub'],
	    ['note' => 'time, seconds', 'f' => 'time', 'cl' => 'pub'],
	    ['note' => 'process ID', 'f' => 'getmypid', 'cl' => 'cli'],
	    ];
	
	$this->aa = $a;
    }
    
    public static function getoid() {
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
