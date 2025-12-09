import pyudev
import time
import sys
import signal

# ------------------------------------------------------------------
# Configurable part
# ------------------------------------------------------------------
DEFAULT_TIMEOUT = 20
KEYWORDS = {"add", "remove", "kwbattusb"}

# ------------------------------------------------------------------
# Graceful Ctrl-C handling
# ------------------------------------------------------------------
interrupted = False

def signal_handler(sig, frame):
    global interrupted
    interrupted = True
    sys.exit(130)                     # standard exit code for SIGINT

signal.signal(signal.SIGINT, signal_handler)

# ------------------------------------------------------------------
# Parse timeout argument
# ------------------------------------------------------------------
try:
    arg = sys.argv[1] if len(sys.argv) > 1 else ""
    timeout = int(arg) if arg.isdigit() and int(arg) >= 1 else DEFAULT_TIMEOUT
except:
    timeout = DEFAULT_TIMEOUT

deadline = time.time() + timeout

# ------------------------------------------------------------------
# udev monitoring
# ------------------------------------------------------------------
context = pyudev.Context()
monitor = pyudev.Monitor.from_netlink(context)
monitor.filter_by(subsystem='usb')
monitor.start()

while True:
    if time.time() > deadline:
        print(f"TIMEOUT after {timeout}s")
        sys.exit(124)

    device = monitor.poll(timeout=1)      # 1-second poll so we stay responsive
    if device is None:
        continue

    action = (device.action or "").lower()
    model  = (device.get('ID_MODEL_ID') or "").lower()
    vendor = (device.get('ID_VENDOR_ID') or "").lower()

    line = f"{action} {vendor} {model}"

    if any(k in line for k in KEYWORDS):
        print(f"FOUND: {device.action} {vendor}/{model}".strip())
        sys.exit(1)