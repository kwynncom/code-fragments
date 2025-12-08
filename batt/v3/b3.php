<?php

require_once('/opt/kwynn/kwutils.php');

class battUDevAdmCl {
    public function __construct() {
	$this->watch();
    }

    private function watch() {
	

	$command = 'udevadm monitor -s usb';
	$descriptors = [  1 => ['pipe', 'w'], ];
	$process = proc_open($command, $descriptors, $pipes); unset($command, $descriptors);
	$stdout = $pipes[1]; unset($pipes);

	while ($l = fgets($stdout)) {
	    if ((strpos($l, 'add') !== false) || (strpos($l, 'bind') !== false)) {
		echo('USB connected' . "\n");
		break;
	    }
	} unset($l);

	fclose($stdout); unset($stdout);

	proc_terminate($process, SIGTERM);
	proc_close($process); unset($process);
    }

}

new battUDevAdmCl();
