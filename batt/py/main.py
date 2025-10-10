import sys
import threading
from icon import TrayIconCreator
from shell import get_shell_output

UPDATE_INTERVAL_SECONDS = 61
COMMAND =  "php /var/kwynn/batt/code/base.php no"

class BattMain:

    def run_update_loop(self):
        try :
            while not self.stop_event.is_set():
                text = get_shell_output(COMMAND)
                self.iconol.create_tray_icon(text)
                self.stop_event.wait(timeout=UPDATE_INTERVAL_SECONDS)

        except Exception as e:
            print(f"Error loop thread: {str(e)}", file=sys.stderr)
            self.iconol.stop()
            sys.exit(-5)

    def __init__(self):
        self.stop_event = threading.Event()
        self.update_thread = threading.Thread(target=self.run_update_loop, daemon=True)
        self.iconol = TrayIconCreator(self.update_thread, self.stop_event)
        self.update_thread.start()
        self.iconol.run()  # blocking

if __name__ == "__main__":
    BattMain()

