import pystray
from PIL import Image, ImageDraw, ImageFont
import os

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
    icon = pystray.Icon("battLevKw")
    icon.icon = image
    icon.title = "battery lev"
    icon.run()

if __name__ == "__main__":
    create_tray_icon("42")