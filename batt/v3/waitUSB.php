<?php

require_once('utils.php');

use React\ChildProcess\Process;
use React\EventLoop\Loop;

class usbWaitCl {

    public static function wait() {
	$o = new self();
	$o->waitI();
    }


private readonly mixed $cb;
private readonly object $loop;

private function waitI(callable $cb = null) {
    $this->cb = $cb; unset($cb);
    if (!isset($this->loop)) $this->loop = Loop::get();





    $process = new Process($this->getCmd(),
	
	null,
	null,
	null,
	['pty' => true, 'pty_columns' => 120, 'pty_rows' => 40]  // CRITICAL: pty + size
    );

    $process->start();

    echo "Waiting for USB device add event (plug in any USB device)...\n";


    $process->stdout->on('data', function ($chunk) {
	$this->ondata($chunk);
    });


    $this->loop->run();

    } // func
 
    private function ondata(string $data) {

	Loop::get()->stop();
	echo "udev: $data";
	onUsbDeviceAdded();
	sleep(2);
	if ($this->cb) ($this->cb)();
    }

    private function getCmd() : string {
$script = <<<'BASH'
#!/usr/bin/env bash
set -euo pipefail

COMMAND=(udevadm monitor -s usb)
KEYWORDS=("add")

exec "${COMMAND[@]}" |
while IFS= read -r line; do
    lowered="${line,,}"
    for kw in "${KEYWORDS[@]}"; do
	if [[ "$lowered" == *"${kw,,}"* ]]; then
	    echo "FOUND: USB device added!"
	    echo "$line"
	    exit 0
	fi
    done
done
BASH;

	$cmd = 'bash -c ' . escapeshellarg($script);

	return $cmd;
    }


}

   function onUsbDeviceAdded(string $from = '')
    {
	echo('You can now run adb, flash firmware, mount drive, etc. From ' . "$from\n");
	// Example:
	// (new Process('adb wait-for-device'))->start();
    }

if (didCLICallMe(__FILE__)) {
    usbWaitCl::wait();
}