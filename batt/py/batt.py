import pystray
from PIL import Image, ImageDraw, ImageFont
import os

icon_size = (24, 24)
image = Image.new("RGBA", icon_size, (0, 0, 0, 0))
font_name = "Ubuntu-B.ttf"
font = ImageFont.truetype(font_name, 24)
draw = ImageDraw.Draw(image)
text = "42"
text_bbox = draw.textbbox((0, 0), text, font=font)
text_width = text_bbox[2] - text_bbox[0]
text_height = text_bbox[3] - text_bbox[1]
text_x = (icon_size[0] - text_width) // 2
text_y = (icon_size[1] - text_height) // 2 - 2
draw.text((text_x, text_y), text, font=font, fill=(255, 255, 255, 255))
image.save(f"/tmp/icon_42_{font_name.split('.')[0]}.png")
icon = pystray.Icon("number_42")
icon.icon = image
icon.title = "Number 42"
icon.run()