<?php

require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../arr.php');

class hoursINotifyCl {

    const spre = 2;

    const cmd = 'inotifywait -m ' . hoursIntf::glob  . ' -e modify';

    private readonly mixed $h;
 
    public function __construct() {
	$this->init();
	$this->do10();
    }

    public function __destroy() {
	if (!isset($this->h) || !$this->h) return;
	pclose($this->h);
    }

    private function init() {
	shell_exec('pkill -x -f ' . '"' . self::cmd . '"');
	$this->h = popen(self::cmd, 'r');
	if (!$this->h) { die("Failed to open pipe to command\n");	}
    }

    private function do10() {
	while (!feof($this->h)) {  
	    echo(fgets($this->h));
	    $this->do20(); 	
	}
    }

    private function do20() {
	static $U = 0;
	$t = microtime(true);
	if ($t - $U > 2) { 
	    echo('Calling arr' . "\n");
	    new odsFirstSheetCl(); 
	}
	$U = $t;
    }

    

}

if (didCLICallMe(__FILE__)) new hoursINotifyCl();


 