<?php


require_once('utils.php');
require_once('usb.php');


class battExtCl {

    const nMaxLoop = 50; //	PHP_INT_MAX

    public function __construct() {
	$this->killPrev();
	self::bout('init');
	$this->monitor();
    }

    private function killPrev() {
	global $argv;

	$sub = 'php ' . implode(' ', $argv);
	$cmd = 'pkill -x -f ' . '"' . $sub . '"';
	belg($cmd);
	// shell_exec($cmd);
	
    }

    public function __destruct() { $this->exit();  }

    public function exit() {
	self::bout('');
	belg('b3 e-xit called' . "\n");
	exit(0);
    }

    private function monitor() {

	for($i=0; $i < self::nMaxLoop; $i++) { 
	    
	    belg('checking level' . "\n");
	    $o = USBADBCl::getLevel();
	    if ($o->noPerm) $o = $this->seekPerm();
	    self::outvlev($o->level);
	}

	beout('b3 mon loop time/n out');

	belg('e-xit per normal (for now) max loop after n iterations === ' . $i);
    }

    private static function outvlev(int $lev) {
	if ($lev < 0) {
	     self::bout('');
	     
	}
	else {
	    self::bout($lev);
	}
    }

    private function seekPerm() : object {
	for ($i=0; $i < 45; $i++) {
	    $o = USBADBCl::getLevel();
	    if ($o->noPerm === false && $o->valid) {
		beout('');
		break;
	    }

	    beout('need permission');

	    sleep(1);
	}
	return $o;
    }

    private static function bout(string $s) { beout($s);  }

}

new battExtCl();
