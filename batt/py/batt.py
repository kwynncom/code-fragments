import pystray
from PIL import Image, ImageDraw, ImageFont
import os
import subprocess
import sys
import threading
import time

UPDATE_INTERVAL_SECONDS = 61

def create_tray_icon(text):
    icon_size = (24, 24)
    image = Image.new("RGBA", icon_size, (0, 0, 0, 0))
    font_name = "Ubuntu-B.ttf"
    font = ImageFont.truetype(font_name, 24)
    draw = ImageDraw.Draw(image)
    text_bbox = draw.textbbox((0, 0), text, font=font)
    text_width = text_bbox[2] - text_bbox[0]
    text_height = text_bbox[3] - text_bbox[1]
    text_x = (icon_size[0] - text_width) // 2
    text_y = (icon_size[1] - text_height) // 2 - 2
    draw.text((text_x, text_y), text, font=font, fill=(255, 255, 255, 255))
    return image

def get_shell_output(command):
    try:
        result = subprocess.check_output(command, shell=True, text=True).strip()
        if result.isdigit() and 1 <= len(result) <= 2:
            return result
        else:
            raise ValueError("Command output is not a 1- or 2-digit number")
    except subprocess.CalledProcessError:
        raise RuntimeError("Command execution failed")

def run_update_loop(icon, command):
    while True:
        text = get_shell_output(command)
        icon.icon = create_tray_icon(text)

        time.sleep(UPDATE_INTERVAL_SECONDS)

if __name__ == "__main__":
    # command = "echo 42"  # Replace with your desired shell command
    command = "php /var/kwynn/batt/code/base.php no"
    try:
        initial_text = get_shell_output(command)
        icon = pystray.Icon("cell battery", title="cell battery")
        icon.icon = create_tray_icon(initial_text)
        
        update_thread = threading.Thread(target=run_update_loop, args=(icon, command), daemon=True)
        update_thread.start()
        
        icon.run()
    except (ValueError, RuntimeError) as e:
        print(f"Error: {str(e)}", file=sys.stderr)
        icon.stop()  # Stop the system tray icon
        sys.exit(1)
