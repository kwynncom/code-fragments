<?php

require_once('/opt/kwynn/kwutils.php');
require_once('/opt/kwynn/lock.php');

class boot20 {
    
    const bootTimeMarginOfErrorS = 3;
    const fifo = '/tmp/kwbootd';
    
    private function __construct() { 
	$this->iamd = false;
	$this->p10();
    }
    
    public function __destruct() {
	if (!$this->iamd) return;
	unlink(self::fifo);
    }
    
    private function p10() {

	if (!file_exists(self::fifo)) {
	    $so = new sem_lock(__FILE__);
	    $so->lock();
	    if (!file_exists(self::fifo)) $this->setup10($so);
	    else $so->unlock();
	}
	ini_set('default_socket_timeout', 1);
	$r = fopen(self::fifo, 'r');
	echo(fread($r, 10) . "\n");
	fclose($r);
    }
    
    public function setup10($so) {
	posix_mkfifo(self::fifo, 0600);
	$so->unlock();
	chmod(self::fifo, 0600);
	$this->iamd = true;
	$this->setup20();
    }
    
    public function setup20() {
	static $uptime = false;
	if (!$uptime) $uptime = self::uptime();
	$i = 0;

	echo("opening\n");
	$r = fopen(self::fifo, 'w');
	do {

	    echo("writing\n");
	    fwrite($r, $uptime, strlen($uptime));
	    // file_put_contents(self::fifo, $uptime);
	    echo("post write; i = $i \n");

	} while(++$i < 100);

	fclose($r);

    }
    
    public static function uptime() {
	$uo = uptime();
	return $uo['Ubest'];
    }
    
    public static function get() {
	$o = new self();
    }
}

if (didCLICallMe(__FILE__)) boot20::get();