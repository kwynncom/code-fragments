import sys
import threading
import time
from icon import TrayIconCreator
from shell import get_shell_output

UPDATE_INTERVAL_SECONDS = 61
COMMAND =  "php /var/kwynn/batt/code/base.php no"

def run_update_loop(iconoin):
    try :
        while True:
            text = get_shell_output(COMMAND)
            iconoin.create_tray_icon(text)
            time.sleep(UPDATE_INTERVAL_SECONDS)
    except Exception as e:
        print(f"Error loop thread: {str(e)}", file=sys.stderr)
        iconoin.stop()
        sys.exit(-5)

def initIcon():
    iconol = TrayIconCreator()
    update_thread = threading.Thread(target=run_update_loop, args=(iconol, ), daemon=True)
    update_thread.start()
    iconol.run()  # blocking

if __name__ == "__main__":
    initIcon()

