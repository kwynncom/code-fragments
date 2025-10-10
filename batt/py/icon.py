
import pystray
import signal
import sys
from pystray import MenuItem as item
from pystray import Menu
from PIL import Image, ImageDraw, ImageFont

class TrayIconCreator:
    def __init__(self, threadIn, stop_event, font_name="Ubuntu-B.ttf", icon_size=(24, 24), font_size=24):
        self.thread = threadIn
        self.stop_event = stop_event
        self.font_name = font_name
        self.icon_size = icon_size
        self.font_size = font_size

        menu = Menu(item('Exit', self.quit))  # Create menu with Exit item
        self.iconP = pystray.Icon("cell battery", title="cell battery", menu=menu)
        signal.signal(signal.SIGTERM, self.signal_handler)  # Handles kill/termination
        signal.signal(signal.SIGINT , self.signal_handler)  # Handles Ctrl+C
        self.create_tray_icon('??')

    def quit(self):
        self.stop_event.set()
        self.thread.join()
        self.iconP.stop()
        sys.exit(0)

    def signal_handler(self, sig, frame):
        self.quit()

    def create_tray_icon(self, text):
        image = Image.new("RGBA", self.icon_size, (0, 0, 0, 0))
        font = ImageFont.truetype(self.font_name, self.font_size)
        draw = ImageDraw.Draw(image)
        text_bbox = draw.textbbox((0, 0), text, font=font)
        text_width = text_bbox[2] - text_bbox[0]
        text_height = text_bbox[3] - text_bbox[1]
        text_x = (self.icon_size[0] - text_width) // 2
        text_y = (self.icon_size[1] - text_height) // 2 - 2
        draw.text((text_x, text_y), text, font=font, fill=(255, 255, 255, 255))
        self.iconP.icon = image

    def run(self):
        self.iconP.run()

    def stop(self):
        self.iconP.stop()