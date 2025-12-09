#!/bin/bash
# monitor-usb.sh

TIMEOUT=25

timeout --signal=TERM --kill-after=5 "$TIMEOUT" \
  udevadm monitor -s usb 2>&1 |
stdbuf -oL tr '[:upper:]' '[:lower:]' |
while IFS= read -r line; do
    if [[ "$line" == *add* ]] || [[ "$line" == *remove* ]] || [[ "$line" == *kwbattusb* ]]; then
        echo "DETECTED: $line"
        exit 1
    fi
done

# only reached on real timeout
echo "TIMEOUT"
exit 124