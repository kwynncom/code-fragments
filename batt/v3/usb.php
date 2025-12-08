<?php

require_once('adb.php');


class USBADBCl extends adbCl {


    public bool|null $usb;

    private readonly int $timeout;

    public static function getLevel(int $timeout = 0) {
	$o = new self($timeout);
	return $o;
    }

    private function __construct(int $timeout) {
	$this->timeout = $timeout;
	$this->doit();
    }


    private function doit() : object {

	    $n = 3;

	    for($i = 0; $i < $n; $i++) {

		if ($this->timeout || $i !== 0) $this->usb = $this->monitorUSB();
		$o = $this->setADB($i === 0 && !$this->timeout);
		if (isset($o) && ($o->level >= 0 || $o->noPerm || (($this->usb ?? null) === false))) {
		    return $o;
		}



	    }

	    return $o;
	

	

    }

    private function monitorUSB() : bool | null {

	$c = '';

	if ($this->timeout > 0) $c .= 'timeout ' . $this->timeout . ' ';

    
	$c .= 'udevadm monitor -s usb';
	$descriptors = [  1 => ['pipe', 'w'], ];
	$process = proc_open($c, $descriptors, $pipes); unset($c, $descriptors);
	$stdout = $pipes[1]; unset($pipes);

	while ($l = fgets($stdout)) {
	    $add = strpos($l, 'add') !== false;
	    $rm  = strpos($l, 'remove') !== false;
	    if ($add || $rm) {
		break;
	    }
	} unset($l);

	fclose($stdout); unset($stdout);

	proc_terminate($process, SIGTERM);
	proc_close($process); unset($process);

	if ($add) return true;
	if ($rm ) return false;
	return null;

    }
}

