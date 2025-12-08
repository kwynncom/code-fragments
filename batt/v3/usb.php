<?php

require_once('adb.php');


class battUDevAdmCl {

    public function getLevel() {
	
    }


    private function initWatch() : object {
	
	self::bout('init');

	$o = adbCl::getLevel();
	if ($o->level >= 0) {
	    self::bout($o->msg);
	    return $o;
	}

	
	self::bout('seeking');

	$command = 'udevadm monitor -s usb';
	$descriptors = [  1 => ['pipe', 'w'], ];
	$process = proc_open($command, $descriptors, $pipes); unset($command, $descriptors);
	$stdout = $pipes[1]; unset($pipes);

	self::bout('waiting');

	while ($l = fgets($stdout)) {		    // do NOT want to match " unbind "
	    if ((strpos($l, 'add') !== false) || (strpos($l, ' bind') !== false)) {
		self::bout('USB connected');
		$o = adbCl::getLevel();
		self::bout($o->msg);
		if ($o->noPerm) $o = $this->seekPerm();
		break;
	    }
	} unset($l);

	fclose($stdout); unset($stdout);

	proc_terminate($process, SIGTERM);
	proc_close($process); unset($process);

	return $o;
    }
}

new battUDevAdmCl();
