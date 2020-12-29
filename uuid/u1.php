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
	    ['note' => 'MongoDB OID original and human-readable', 'f' => ['uuidcl', 'oid'], 'cl' => 'pub'],
	    ['note' => 'my own extension - TSC, PID', 'f' => 'rdtscp', 'cl' => 'cli'],
	    ['note' => 'my own extension - nanotime', 'f' => 'nanotime', 'cl' => 'pub'],
	    ['note' => 'time, seconds', 'f' => 'time', 'cl' => 'pub'],
	    ['note' => 'process ID', 'f' => 'getmypid', 'cl' => 'cli'],
	    ['note' => 'my new machine ID stuff', 'f' => ['uuidcl', 'mid'], 'cl' => 'cli'],
	    ['note' => 'rough-yet-absurdly precise kwynn.com' , 'f' => ['uuidcl', 'ec2_phy'], 'cl' => 'cli'],
	    ];
	
	$this->aa = $a;
    }
    
    public static function mid() {
	$c = ['machine_id', 'get'];
	if (!(class_exists($c[0]))) return '';
	return $c();
    }
    
    public static function ec2_phy() {
	$t = file_get_contents(__DIR__ . '/AWS_EC2_loc.txt');
	$k = '---DATA---';
	$b = strpos($t, $k);
	return trim(substr($t, $b + strlen($k)));
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
