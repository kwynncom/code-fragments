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
	echo('b3 exit called' . "\n");
	exit(0);
    }



    private function monitor() {
	for($i=0; $i < 12; $i++) {
	    
	    echo('checking level' . "\n");


	    $o = USBADBCl::getLevel();

	    if (($o->usb ?? null) === false) {
		self::bout('USB disconnect.  Exiting...');
		sleep(3);
		$this->exit();
	    }

	    if ($o->noPerm) $o = $this->seekPerm();
 
	    self::outvlev($o->level);



	    $n = 0;
	    echo('sleep outer ' . $n . "\n");
	    if ($n) sleep($n);
	}

	self::bout('exit per normal (for now) max loop');
    }

    private static function outvlev(int $lev) {
	if ($lev < 0) 
	     self::bout('no connection');
	else self::bout($lev);
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
