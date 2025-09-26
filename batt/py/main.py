import pystray
import sys
import threading
import time
from icon import TrayIconCreator
from shell import get_shell_output

UPDATE_INTERVAL_SECONDS = 61
COMMAND =  "php /var/kwynn/batt/code/base.php no"

icon = None
icon_creator = None

def init_icon() :
    global icon, icon_creator
    icon_creator = TrayIconCreator()
    icon = pystray.Icon("cell battery", title="cell battery")
    icon.icon = icon_creator.create_tray_icon('??')

def run_update_loop():

    while True:
        text = get_shell_output(COMMAND)
        icon.icon = icon_creator.create_tray_icon(text)
        time.sleep(UPDATE_INTERVAL_SECONDS)

if __name__ == "__main__":

    try:
        init_icon()
        update_thread = threading.Thread(target=run_update_loop, args=(), daemon=True)
        update_thread.start()
        icon.run()

    except (ValueError, RuntimeError) as e:
        print(f"Error: {str(e)}", file=sys.stderr)
        icon.stop()
        sys.exit(1)