#!/usr/bin/env python3
# usb_monitor.py – works from PHP shell_exec() without any issues

import pyudev
import time
context = pyudev.Context()
monitor = pyudev.Monitor.from_netlink(context)
monitor.filter_by(subsystem='usb')   # only usb events
monitor.start()                      # <─ THIS IS REQUIRED

timeout   = 20
deadline  = time.time() + timeout
keywords  = {"add", "remove", "kwbattusb"}

# poll() returns a single device or None
while True:
    device = monitor.poll(timeout=1)          # 1-second poll so we can check deadline
    if device is None:                        # poll timeout (no event)
        if time.time() > deadline:
            print("TIMEOUT after 20s")
            raise SystemExit(124)
        continue

    # device is a pyudev.Device object here
    action = (device.action or "").lower()
    model  = (device.get('ID_MODEL_ID') or "").lower()
    vendor = (device.get('ID_VENDOR_ID') or "").lower()

    line = f"{action} {vendor} {model}"

    if any(k in line for k in keywords):
        print(f"FOUND: {action.upper()} {vendor}/{model}")
        raise SystemExit(1)