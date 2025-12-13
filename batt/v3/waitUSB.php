<?php

require_once('utils.php');

use React\ChildProcess\Process;
use React\EventLoop\Loop;

class usbWaitCl {

public static function wait(callable $cb) {
    $o = new self();
    $o->waitI($cb);
}

private readonly mixed $cb;

private function waitI(callable $cb) {
    $this->cb = $cb; unset($cb);
    $loop = Loop::get();
    $process = new Process($this->getCmd(), null, null, null,['pty' => true, 'pty_columns' => 120, 'pty_rows' => 40]);
    $process->start();
    echo "Waiting for USB device add event (plug in any USB device)...\n";
    $process->stdout->on('data', function ($chunk) { 	$this->ondata($chunk);     });
    $loop->run();

} // func
 
private function ondata(string $data) {

    Loop::get()->stop();
    echo('found usb'. PHP_EOL);
    ($this->cb)();
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
} // func
} // class

if (didCLICallMe(__FILE__)) {
    usbWaitCl::wait(); // need callable
}