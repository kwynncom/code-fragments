<?php

require_once('/opt/kwynn/kwutils.php');
require_once('config.php');
require_once('usb.php');


class battExtCl {
    public function __construct() {
	self::bout('init');
	$this->monitor();
    }

    public function __destruct() { $this->exit();  }

    public function exit() {
	self::bout('');
	echo('b3 e-xit called' . "\n");
	exit(0);
    }



    private function monitor() {

	for($i=0; $i < PHP_INT_MAX; $i++) { //	PHP_INT_MAX
	    
	    echo('checking level' . "\n");
	    $o = USBADBCl::getLevel();
	    if ($o->noPerm) $o = $this->seekPerm();
	    self::outvlev($o->level);
	}

	self::bout('e-xit per normal (for now) max loop after n iterations === ' . $i);
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
	    if ($o->noPerm === false) break;
	    sleep(1);
	}
	return $o;
    }

    private static function bout(string $s) { beout($s);  }

}

new battExtCl();
