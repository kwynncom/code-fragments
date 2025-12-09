#!/usr/bin/env python3
# usb_monitor.py
# Wait for USB add/remove or KWBATTUSB device, with configurable timeout
# Usage: ./usb_monitor.py          → 20 seconds (default)
#        ./usb_monitor.py 45       → 45 seconds
# Exit 1  → keyword found
# Exit 124 → timeout
# written by Grok (Fast Mode, v3.x-ish)

import pyudev
import time
import sys

# Default timeout = 20 seconds
default_timeout = 20

# Parse optional timeout from command line (must be ≥1)
try:
    arg = sys.argv[1] if len(sys.argv) > 1 else ""
    timeout = int(arg) if arg.isdigit() and int(arg) >= 1 else default_timeout
except:
    timeout = default_timeout

deadline = time.time() + timeout
keywords = {"add", "remove", "kwbattusb"}

context = pyudev.Context()
monitor = pyudev.Monitor.from_netlink(context)
monitor.filter_by(subsystem='usb')
monitor.start()

while True:
    device = monitor.poll(timeout=1)  # 1-second poll so we can check deadline
    if device is None:
        if time.time() > deadline:
            print(f"TIMEOUT after {timeout}s")
            sys.exit(124)
        continue

    action = (device.action or "").lower()
    model  = (device.get('ID_MODEL_ID') or "").lower()
    vendor = (device.get('ID_VENDOR_ID') or "").lower()

    line = f"{action} {vendor} {model}"

    if any(k in line for k in keywords):
        print(f"FOUND: {device.action} {vendor}/{model}".strip())
        sys.exit(1)