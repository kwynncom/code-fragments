<?php

require_once('utils.php');

use React\ChildProcess\Process;
use React\EventLoop\Loop;

$loop = Loop::get();

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

$process = new Process(
    'bash -c ' . escapeshellarg($script),
    null,
    null,
    null,
    ['pty' => true, 'pty_columns' => 120, 'pty_rows' => 40]  // CRITICAL: pty + size
);

$process->start();

echo "Waiting for USB device add event (plug in any USB device)...\n";

$process->stdout->on('data', function ($chunk) {
    echo "udev: $chunk";
});

$process->stderr->on('data', function ($chunk) {
    echo "udev error: $chunk";
});

$process->on('exit', function ($code) use ($loop) {
    if ($code === 0) {
        echo "\nUSB DEVICE DETECTED! Doing your thing now...\n";
        // ←←← Your real code goes here
        onUsbDeviceAdded();
    } else {
        echo "\nudev script exited with code $code (probably killed)\n";
    }
    // Keep waiting for next device? Restart:
    // Loop::addTimer(1, __NAMESPACE__ . '\startWatcher');
});

$loop->run();

function onUsbDeviceAdded()
{
    echo "You can now run adb, flash firmware, mount drive, etc.\n";
    // Example:
    // (new Process('adb wait-for-device'))->start();
}