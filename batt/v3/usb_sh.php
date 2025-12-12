<?php

function getUSBPHPMonitorF() : string {

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